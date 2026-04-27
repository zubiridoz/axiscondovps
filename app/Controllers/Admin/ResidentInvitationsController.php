<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\InvitationService;
use App\Services\ResidentImportService;

class ResidentInvitationsController extends BaseController
{
    /**
     * POST /admin/residentes/invite
     * Endpoint para invitar a un solo residente desde el modal "Añadir residente"
     */
    public function invite()
    {
        // 1. Obtener datos
        $data = [
            'name'  => $this->request->getPost('name') ?? $this->request->getJSON(true)['name'] ?? '',
            'email' => $this->request->getPost('email') ?? $this->request->getJSON(true)['email'] ?? '',
            'unit_id' => $this->request->getPost('unit_id') ?? $this->request->getJSON(true)['unit_id'] ?? null,
            'role'  => $this->request->getPost('role') ?? $this->request->getJSON(true)['role'] ?? 'owner'
        ];

        // Validaciones básicas manuales (CI4 Validation rule builder se podría usar alternativamente)
        if (empty($data['name']) || empty($data['email'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nombre y correo son requeridos']);
        }

        // 2. Obtener contexto (Tenant / Admin)
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        $condoId = $demoCondo ? (int)$demoCondo['id'] : 0;
        
        // Asume usuario auth en un entorno real
        $invitedBy = 1; 

        if ($condoId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no válido']);
        }

        // 3. Pasar a servicio
        $invitationService = new InvitationService();
        $error = $invitationService->createInvitation($condoId, $data, $invitedBy);

        if ($error) {
            return $this->response->setJSON(['success' => false, 'message' => $error]);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Invitación enviada exitosamente']);
    }

    /**
     * POST /admin/residentes/import
     * Sube el CSV, lo parsea y devuelve el JSON preview (Paso 2)
     */
    public function importPreview()
    {
        $file = $this->request->getFile('file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido']);
        }

        // Validar tamaño 10MB
        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->response->setJSON(['success' => false, 'message' => 'El archivo supera los 10MB permitidos']);
        }

        $importService = new ResidentImportService();
        $rows = $importService->parseCSV($file->getTempName()); // CSV parse

        $valid = [];
        $errors = [];
        
        // Simple logic for valid/errors arrays
        foreach($rows as $r) {
            if(empty($r['name']) || empty($r['email'])) {
                $errors[] = $r;
            } else {
                $valid[] = $r;
            }
        }

        $cacheKey = 'csv_import_' . md5(uniqid(rand(), true));
        cache()->save($cacheKey, $valid, 3600); // 1 hour

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Preview generada correctamente',
            'data' => [
                'cache_key' => $cacheKey,
                'valid' => $valid,
                'errors' => $errors
            ]
        ]);
    }

    /**
     * POST /admin/residentes/import/confirm
     * Recibe la cache_key de los datos ya subidos (Preview) y ejecuta invitaciones
     */
    public function importProcess()
    {
        $json = $this->request->getJSON(true);
        $cacheKey = $json['cache_key'] ?? null;
        $notify = $json['notify'] ?? true;
        $frontendRows = $json['rows'] ?? null;

        // Use frontend-edited rows if provided, otherwise use cached data
        $rows = null;
        if (!empty($frontendRows) && is_array($frontendRows)) {
            $rows = $frontendRows;
        } elseif ($cacheKey) {
            $rows = cache($cacheKey);
        }

        if (empty($rows)) {
            return $this->response->setJSON(['success' => false, 'message' => 'La sesión de importación ha expirado o es inválida. Intenta subir el archivo de nuevo.']);
        }

        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        $condoId = $demoCondo ? (int)$demoCondo['id'] : 0;
        $invitedBy = 1;

        $invitationService = new InvitationService();
        $unitModel = new \App\Models\Tenant\UnitModel();
        
        $successCount = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $unitId = null;
            if (!empty($row['unit'])) {
                $u = $unitModel->where('condominium_id', $condoId)->where('unit_number', $row['unit'])->first();
                if ($u) {
                    $unitId = $u['id'];
                }
                // If unit not found, still proceed but without unit assignment
            }

            // Map role display name back to DB value if needed
            $role = $row['role'] ?? 'owner';
            $roleLower = strtolower($role);
            if (strpos($roleLower, 'propie') !== false) $role = 'owner';
            elseif (strpos($roleLower, 'inqui') !== false) $role = 'tenant';
            elseif (strpos($roleLower, 'admin') !== false) $role = 'admin';

            $data = [
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $row['phone'] ?? null,
                'role' => $role,
                'unit_id' => $unitId
            ];

            $error = $invitationService->createInvitation($condoId, $data, $invitedBy, $notify);
            
            if ($error) {
                $errors[] = "({$row['email']}): " . $error;
            } else {
                $successCount++;
            }
        }

        // Clean cache if used
        if ($cacheKey) {
            cache()->delete($cacheKey);
        }

        return $this->response->setJSON([
            'success' => true, 
            'message' => "Proceso terminado.",
            'data' => [
                'invited' => $successCount,
                'errors' => $errors
            ]
        ]);
    }

    /**
     * POST /admin/residentes/invitaciones/actualizar
     */
    public function update()
    {
        $id = $this->request->getPost('id');
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $unit_id = $this->request->getPost('unit_id');
        $role = $this->request->getPost('role');

        if (!$id || !$name || !$email) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos obligatorios.']);
        }

        $invitationModel = new \App\Models\Tenant\ResidentInvitationModel();
        
        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'unit_id' => empty($unit_id) ? null : $unit_id,
            'role' => $role
        ];

        if ($invitationModel->update($id, $data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Invitación actualizada con éxito.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar invitación.']);
    }

    /**
     * POST /admin/residentes/invitaciones/eliminar
     */
    public function delete()
    {
        $id = $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['success' => false, 'message' => 'Falta el ID de la invitación.']);

        $invitationModel = new \App\Models\Tenant\ResidentInvitationModel();
        
        // El usuario solicitó eliminarla físicamente para permitir reenviar 'otra vez con ese mismo correo'
        if ($invitationModel->delete($id, true)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Invitación eliminada correctamente.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al eliminar invitación.']);
    }

    /**
     * POST /admin/residentes/invitaciones/reenviar
     */
    public function resend()
    {
        $id = $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['success' => false, 'message' => 'Falta el ID de la invitación.']);

        $invitationModel = new \App\Models\Tenant\ResidentInvitationModel();
        $invitation = $invitationModel->find($id);

        if (!$invitation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invitación no encontrada.']);
        }

        $nuevoFecha = date('Y-m-d H:i:s');
        $data = [
            'invited_at' => $nuevoFecha,
            'invitation_status' => 'pending'
        ];
        
        $invitationModel->update($id, $data);
        
        return $this->response->setJSON(['success' => true, 'message' => 'Invitación reenviada con éxito', 'invited_at_raw' => $nuevoFecha]);
    }
}
