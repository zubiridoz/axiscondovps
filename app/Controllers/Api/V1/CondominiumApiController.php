<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

/**
 * CondominiumApiController
 * 
 * Endpoints de lectura y cambio de condominio para la app Flutter.
 * NO crea condominios (eso se hace desde el panel web).
 * 
 * Endpoints:
 *  GET  /api/v1/condominiums/mine   → Lista condominios del usuario
 *  POST /api/v1/condominiums/switch → Cambia el condominio activo
 */
class CondominiumApiController extends ResourceController
{
    /**
     * Lista todos los condominios a los que pertenece el usuario autenticado.
     * Retorna nombre, logo, ciudad y rol en cada condominio.
     */
    public function mine()
    {
        $userId = (int) $this->request->getHeaderLine('X-Auth-UserId');
        if (!$userId) {
            return $this->failUnauthorized('No se pudo identificar al usuario.');
        }

        $db = \Config\Database::connect();

        // Obtener todos los condominios del usuario con su rol
        $condominiums = $db->table('user_condominium_roles AS ucr')
            ->select('
                c.id,
                c.name,
                c.logo,
                c.address,
                c.currency,
                c.status,
                r.name AS role_name,
                ucr.role_id
            ')
            ->join('condominiums AS c', 'c.id = ucr.condominium_id')
            ->join('roles AS r', 'r.id = ucr.role_id', 'left')
            ->where('ucr.user_id', $userId)
            ->where('c.deleted_at IS NULL')
            ->orderBy('c.name', 'ASC')
            ->get()
            ->getResultArray();

        // Enriquecer con la ciudad (extraída del address) e initial y validar persistencia real
        $finalCondominiums = [];
        foreach ($condominiums as $condo) {
            // Hardening: Si el usuario es un RESIDENTE, debe tener registro en la tabla `residents`
            if (strtoupper($condo['role_name']) === 'RESIDENT') {
                $hasResidentRecord = $db->table('residents')
                    ->where('user_id', $userId)
                    ->where('condominium_id', $condo['id'])
                    ->countAllResults();

                if ($hasResidentRecord === 0) {
                    continue; // Omitir, es un "fantasma" sin limpiar
                }
            }

            $condo['city'] = $this->extractCity($condo['address'] ?? '');
            $condo['initial'] = strtoupper(substr($condo['name'] ?? 'C', 0, 1));
            
            // Construir URL del logo si existe
            if (!empty($condo['logo'])) {
                // Fix image URL to point to public api endpoint
                $condo['logo_url'] = base_url('api/v1/public/image/' . $condo['logo']);
            } else {
                $condo['logo_url'] = null;
            }
            
            $finalCondominiums[] = $condo;
        }

        // Determinar cuál es el activo (el que resolvió ApiAuthFilter)
        $currentTenantId = \App\Services\TenantService::getInstance()->getTenantId();

        return $this->respond([
            'status' => 'success',
            'data'   => [
                'condominiums'    => $finalCondominiums,
                'current_id'      => $currentTenantId,
                'total'           => count($finalCondominiums),
            ]
        ]);
    }

    /**
     * Switch Tenant (STATELESS)
     * 
     * Valida que el usuario pertenece al condominio solicitado y retorna
     * los datos necesarios para que Flutter actualice su contexto local.
     * 
     * NO guarda sesión ni estado en el servidor.
     * El contexto real se resuelve en cada request vía X-Condo-Id header.
     * 
     * Payload: { "condominium_id": 5 }
     * 
     * Response:
     * {
     *   "success": true,
     *   "condominium": { "id": 5, "name": "...", "role": "ADMIN", ... }
     * }
     */
    public function switchTenant()
    {
        $userId = (int) $this->request->getHeaderLine('X-Auth-UserId');
        if (!$userId) {
            return $this->failUnauthorized('No se pudo identificar al usuario.');
        }

        // ── Validar input ──
        $condominiumId = (int) $this->request->getVar('condominium_id');
        if ($condominiumId <= 0) {
            return $this->fail('El ID del condominio es obligatorio.', 400);
        }

        $db = \Config\Database::connect();

        // ── Validar pertenencia ──
        $pivot = $db->table('user_condominium_roles')
            ->where('user_id', $userId)
            ->where('condominium_id', $condominiumId)
            ->get()
            ->getRow();

        if (!$pivot) {
            log_message('warning', "[SECURITY] Switch denegado: user={$userId} a condo={$condominiumId}");
            return $this->failForbidden('No tienes acceso a este condominio.');
        }

        // ── Cargar datos del condominio ──
        $condo = $db->table('condominiums')
            ->where('id', $condominiumId)
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$condo) {
            return $this->failNotFound('Condominio no encontrado.');
        }

        // ── Datos del residente en este condominio (si aplica) ──
        $unitNumber = null;
        $unitId     = null;

        $resident = $db->table('residents')
            ->where('user_id', $userId)
            ->where('condominium_id', $condominiumId)
            ->get()
            ->getRowArray();

        if ($resident && !empty($resident['unit_id'])) {
            $unit = $db->table('units')
                ->select('id, unit_number')
                ->where('id', $resident['unit_id'])
                ->get()
                ->getRowArray();

            if ($unit) {
                $unitNumber = $unit['unit_number'];
                $unitId     = (int) $unit['id'];
            }
        }

        // ── Nombre del rol ──
        $role = $db->table('roles')
            ->where('id', $pivot->role_id)
            ->get()
            ->getRow();

        return $this->respond([
            'success'      => true,
            'message'      => 'Condominio cambiado exitosamente.',
            'condominium'  => [
                'id'          => $condominiumId,
                'name'        => $condo['name'],
                'logo'        => $condo['logo'] ?? null,
                'currency'    => $condo['currency'] ?? 'MXN',
                'role'        => $role ? $role->name : 'RESIDENT',
                'unit_id'     => $unitId,
                'unit_number' => $unitNumber,
                'city'        => $this->extractCity($condo['address'] ?? ''),
            ],
        ]);
    }

    /**
     * Extrae la ciudad del string de dirección (formato: calle, ciudad, estado, cp, país).
     */
    private function extractCity(string $address): string
    {
        if (empty($address)) return '';
        $parts = array_map('trim', explode(',', $address));
        return $parts[1] ?? ($parts[0] ?? '');
    }

    /**
     * Check if user is an admin for the current tenant
     */
    private function isAdmin(int $userId): bool
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        if (!$tenantId) return false;

        $db = \Config\Database::connect();
        $pivot = $db->table('user_condominium_roles')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->whereIn('role_id', [2, 5]) // Admin, Owner
            ->get()
            ->getRow();
        return $pivot !== null;
    }

    /**
     * GET /api/v1/condominiums/settings
     * Return community settings for the current tenant (admin only)
     */
    public function settings()
    {
        $userId = (int) $this->request->getHeaderLine('X-Auth-UserId');
        if (!$userId) return $this->failUnauthorized('No autorizado.');

        if (!$this->isAdmin($userId)) {
            return $this->failForbidden('No tienes permisos de administrador.');
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();

        $condo = $db->table('condominiums')
            ->where('id', $tenantId)
            ->where('deleted_at IS NULL')
            ->get()
            ->getRowArray();

        if (!$condo) return $this->failNotFound('Comunidad no encontrada.');

        // Build logo URL
        $logoUrl = null;
        if (!empty($condo['logo'])) {
            $logoUrl = base_url('api/v1/public/image/' . $condo['logo']);
        }

        return $this->respond([
            'status' => 'success',
            'data'   => [
                'id'                      => (int) $condo['id'],
                'name'                    => $condo['name'] ?? '',
                'address'                 => $condo['address'] ?? '',
                'logo'                    => $condo['logo'] ?? null,
                'logo_url'                => $logoUrl,
                'currency'                => $condo['currency'] ?? 'MXN',
                'timezone'                => $condo['timezone'] ?? 'America/Mexico_City',
                'allow_resident_posts'    => (int) ($condo['allow_resident_posts'] ?? 1),
                'allow_post_comments'     => (int) ($condo['allow_post_comments'] ?? 1),
                'resident_view_comments'  => (int) ($condo['resident_view_comments'] ?? 0),
                'payment_approval_mode'   => $condo['payment_approval_mode'] ?? 'manual',
                'bank_name'               => $condo['bank_name'] ?? '',
                'bank_clabe'              => $condo['bank_clabe'] ?? '',
                'bank_rfc'                => $condo['bank_rfc'] ?? '',
                'bank_card'               => $condo['bank_card'] ?? '',
            ],
        ]);
    }

    /**
     * POST /api/v1/condominiums/settings/update
     * Update community settings (admin only)
     */
    public function updateSettings()
    {
        $userId = (int) $this->request->getHeaderLine('X-Auth-UserId');
        if (!$userId) return $this->failUnauthorized('No autorizado.');

        if (!$this->isAdmin($userId)) {
            return $this->failForbidden('No tienes permisos de administrador.');
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        // Whitelist of updatable fields
        $allowedFields = [
            'name', 'address',
            'allow_resident_posts', 'allow_post_comments', 'resident_view_comments',
            'payment_approval_mode',
            'bank_name', 'bank_clabe', 'bank_rfc', 'bank_card',
        ];

        $data = [];
        foreach ($allowedFields as $field) {
            $value = $this->request->getVar($field);
            if ($value !== null) {
                // Cast boolean toggles
                if (in_array($field, ['allow_resident_posts', 'allow_post_comments', 'resident_view_comments'])) {
                    $data[$field] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
                } else {
                    $data[$field] = trim((string) $value);
                }
            }
        }

        if (empty($data)) {
            return $this->fail('No se enviaron campos para actualizar.', 400);
        }

        $condoModel = new \App\Models\Tenant\CondominiumModel();

        // Bypass tenant scope for update (we are updating by primary key)
        $db = \Config\Database::connect();
        $db->table('condominiums')
            ->where('id', $tenantId)
            ->update($data);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Configuración actualizada correctamente.',
        ]);
    }

    /**
     * POST /api/v1/condominiums/settings/image
     * Upload community logo/cover (admin only)
     */
    public function uploadSettingsImage()
    {
        $userId = (int) $this->request->getHeaderLine('X-Auth-UserId');
        if (!$userId) return $this->failUnauthorized('No autorizado.');

        if (!$this->isAdmin($userId)) {
            return $this->failForbidden('No tienes permisos de administrador.');
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $file = $this->request->getFile('image');
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $this->fail('No se recibió una imagen válida.', 400);
        }

        // Use same directory structure as backend web: writable/uploads/condominiums/{id}/
        $uploadDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'condominiums' . DIRECTORY_SEPARATOR . $tenantId;
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $field = $this->request->getVar('type') === 'cover' ? 'cover_image' : 'logo';

        // Delete previous file if exists
        $db = \Config\Database::connect();
        $condo = $db->table('condominiums')->where('id', $tenantId)->get()->getRowArray();
        if ($condo && !empty($condo[$field])) {
            $oldFile = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $condo[$field];
            if (is_file($oldFile)) unlink($oldFile);
        }

        $ext = $file->getExtension();
        $prefix = $field === 'cover_image' ? 'cover' : 'logo';
        $newName = $prefix . '_' . time() . '.' . $ext;
        $file->move($uploadDir, $newName);

        // Store relative path matching backend: condominiums/{id}/filename
        $relativePath = 'condominiums/' . $tenantId . '/' . $newName;

        $db->table('condominiums')
            ->where('id', $tenantId)
            ->update([$field => $relativePath]);

        return $this->respond([
            'status'  => 'success',
            'message' => 'Imagen actualizada correctamente.',
            'data'    => [
                'filename' => $relativePath,
                'url'      => base_url('admin/configuracion/imagen/' . $relativePath),
            ],
        ]);
    }
}
