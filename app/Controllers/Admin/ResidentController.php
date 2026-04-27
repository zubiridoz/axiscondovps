<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\ResidentModel;

/**
 * ResidentController
 * 
 * Gestión de los residentes asignados al edificio web/admin.
 */
class ResidentController extends BaseController
{
    /**
     * Lista residentes del edificio
     */
    public function index()
    {
        // [HACK LOCAL] Forzamos el contexto Tenant para la sesión de Admins web local
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        // Usamos UserModel de Core para jalar a TODOS los potenciales residentes
        $userModel = new \App\Models\Core\UserModel();
        // Queremos usuarios que tengan rol 'resident' o que ya estén en la tabla de residents
        
        $db = \Config\Database::connect();
        
        // Hacemos un SELECT desde users, left join con residents y units.
        // Como Mínimo necesitamos traer aquellos que ya son 'residents' o que han sido insertados
        // recientemente por el panel (marcados en la BD). Para MVP, traeremos todos los usuarios del sistema
        // pero en un entorno SaaS real, se filtrarían por el `condominium_id` asignado en una tabla pivote user_condominiums.
        
        // VISTA 1: DIRECTORIO -> Muestra residentes que: ya aceptaron invitación, ya tienen usuario, ya están asignados a unidad.
        // Asumiendo que todos los usuarios mostrados aquí pertenecen a este condominio (Tenant Actual)
        $builder = $db->table('residents');
        $builder->select('users.id as user_id, users.first_name, users.last_name, users.phone, users.email, users.avatar, residents.id as p_resident_id, residents.type as res_type, units.unit_number as unit_name');
        $builder->join('users', 'users.id = residents.user_id');
        $builder->join('units', 'units.id = residents.unit_id');
        // Residents table directly validates they exist and are assigned to a unit. We assume these are 'accepted'.
        $builder->where('residents.condominium_id', (int)$demoCondo['id']);
        $builder->where('users.status', 'active');
        $builder->orderBy('users.first_name', 'ASC');
        
        $residents = $builder->get()->getResultArray();
        
        $counts = [
            'all' => 0,
            'owner' => 0,
            'tenant' => 0
        ];

        // Mapear resultado al formato esperado por la vista
        $mappedResidents = [];
        foreach($residents as $r) {
            $type = $r['res_type'] ?? 'owner';
            $mappedResidents[] = [
                'id' => $r['p_resident_id'] ?? null,
                'user_id' => $r['user_id'],
                'first_name' => $r['first_name'],
                'last_name' => $r['last_name'],
                'email' => $r['email'],
                'phone' => $r['phone'],
                'avatar' => $r['avatar'] ?? null,
                'unit_name' => $r['unit_name'] ?? '<i>No asignada</i>',
                'type' => $type,
                'is_active' => 1
            ];

            $counts['all']++;
            if (isset($counts[$type])) {
                $counts[$type]++;
            }
        }

        $unitModel = new \App\Models\Tenant\UnitModel();
        $units = $unitModel->where('condominium_id', (int)$demoCondo['id'])
                           ->orderBy('unit_number', 'ASC')
                           ->findAll();

        return view('admin/residents', [
            'residents' => $mappedResidents,
            'counts' => $counts,
            'units' => $units
        ]);
    }

    /**
     * Crea un registro de residente asociándolo al tenant
     */
    public function create()
    {
        $data = [
            'user_id'   => $this->request->getPost('user_id'),    // ID del System Core User
            'unit_id'   => $this->request->getPost('unit_id'),    // ID de la Unidad que ocupan
            'type'      => $this->request->getPost('type') ?? 'owner',
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        $residentModel = new ResidentModel();
        // BaseTenantModel inserta de fondo el 'condominium_id' de forma segura
        $resId = $residentModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Residente creado', 'id' => $resId]);
    }

    /**
     * Edita los campos permitidos del residente
     */
    public function update($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $data = [
            'type'      => $this->request->getVar('type'),
            'is_active' => $this->request->getVar('is_active'),
        ];
        $data = array_filter($data, fn($value) => !is_null($value) && $value !== '');

        $residentModel = new ResidentModel();
        if (!$residentModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Residente no encontrado']);
        }

        $residentModel->update($id, $data);

        return $this->response->setJSON(['status' => 200, 'message' => 'Residente actualizado exitosamente']);
    }

    /**
     * Asigna directamente una unidad existente al residente
     */
    public function assignUnit($residentId = null)
    {
        $unitId = $this->request->getPost('unit_id');
        
        if (!$residentId || !$unitId) {
             return $this->response->setJSON(['status' => 400, 'error' => 'IDs incompletos']);
        }
        
        $residentModel = new ResidentModel();
        // Verifica que él le pertenezca a este Tenant
        if ($residentModel->find($residentId)) {
            $residentModel->update($residentId, ['unit_id' => $unitId]);
            return $this->response->setJSON(['status' => 200, 'message' => 'Unidad asignada al residente']);
        }
        
        return $this->response->setJSON(['status' => 404, 'error' => 'Residente no encontrado']);
    }

    /**
     * Toggle activa o desactiva a un residente
     */
    public function toggleActive($id = null)
    {
        $residentModel = new ResidentModel();
        $resident = $residentModel->find($id);
        
        if ($resident) {
            $newState = $resident['is_active'] ? 0 : 1;
            $residentModel->update($id, ['is_active' => $newState]);
            $msg = $newState ? 'Activado' : 'Desactivado';
            
            return $this->response->setJSON(['status' => 200, 'message' => "Estado de residente: $msg"]);
        }
        
        return $this->response->setJSON(['status' => 404, 'error' => 'Residente no encontrado']);
    }

    /**
     * Vista de invitaciones pendientes
     */
    public function invitations()
    {
        
        // Forzar contexto para demo
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        $condoId = $demoCondo ? (int)$demoCondo['id'] : 0;
        
        if ($condoId > 0) {
            \App\Services\TenantService::getInstance()->setTenantId($condoId);
        }

        // Obtenemos de la base de datos real (resident_invitations)
        $invitationModel = new \App\Models\Tenant\ResidentInvitationModel();
        
        // Auto-reconciliar: marcar como 'accepted' invitaciones cuyo email
        // ya existe en la tabla de residentes con unidad asignada
        $db = \Config\Database::connect();
        $staleInvitations = $db->table('resident_invitations ri')
            ->select('ri.id')
            ->join('users u', 'u.email = ri.email')
            ->join('residents r', 'r.user_id = u.id AND r.condominium_id = ri.condominium_id')
            ->where('ri.condominium_id', $condoId)
            ->where('ri.invitation_status', 'pending')
            ->where('r.unit_id IS NOT NULL')
            ->where('r.unit_id !=', 0)
            ->get()->getResultArray();

        if (!empty($staleInvitations)) {
            $staleIds = array_column($staleInvitations, 'id');
            $db->table('resident_invitations')
               ->whereIn('id', $staleIds)
               ->update([
                   'invitation_status' => 'accepted',
                   'accepted_at' => date('Y-m-d H:i:s')
               ]);
        }

        // Solo mostrar invitaciones pendientes
        $dbInvitations = $invitationModel->where('condominium_id', $condoId)
                                         ->where('invitation_status', 'pending')
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();
                                         
        // Mapeamos a la vista
        $invitations = [];
        $unitModel = new \App\Models\Tenant\UnitModel();
        $unitsCache = [];
        
        foreach ($dbInvitations as $inv) {
            $unitName = '-';
            if (!empty($inv['unit_id'])) {
                if (!isset($unitsCache[$inv['unit_id']])) {
                    $u = $unitModel->find($inv['unit_id']);
                    $unitsCache[$inv['unit_id']] = $u ? $u['unit_number'] : '-';
                }
                $unitName = $unitsCache[$inv['unit_id']];
            }
            
            // Traducir status visualmente
            $statusStr = 'Pendiente';
            if ($inv['invitation_status'] == 'accepted') $statusStr = 'Aceptada';
            if ($inv['invitation_status'] == 'cancelled') $statusStr = 'Cancelada';
            if ($inv['invitation_status'] == 'expired') $statusStr = 'Expirada';
            
            // Traducir role visulamente
            $roleStr = 'Propietario';
            if ($inv['role'] == 'tenant') $roleStr = 'Inquilino';
            if ($inv['role'] == 'admin') $roleStr = 'Administrador';

            $invitations[] = [
                'id' => $inv['id'],
                'name' => $inv['name'],
                'email' => $inv['email'],
                'phone' => $inv['phone'] ?? '',
                'role_raw' => $inv['role'],
                'unit_id' => $inv['unit_id'] ?? '',
                'unit' => $unitName,
                'type' => $roleStr,
                'last_invite' => $inv['invited_at'] ? date('d/m/Y', strtotime($inv['invited_at'])) : '-',
                'last_invite_formatted' => $inv['invited_at'] ? date('j \d\e F \d\e Y, H:i', strtotime($inv['invited_at'])) : '-',
                'status' => $statusStr,
                'status_raw' => $inv['invitation_status']
            ];
        }

        // Misma lógica para el modal de invitación (Unidades)
        $units = [];
        if ($condoId > 0) {
            $unitModel = new \App\Models\Tenant\UnitModel();
            $units = $unitModel->where('condominium_id', $condoId)
                               ->orderBy('unit_number', 'ASC')
                               ->findAll();
        }

        return view('admin/invitations', [
            'invitations' => $invitations,
            'units' => $units,
            'counts' => ['pending' => count($invitations)]
        ]);
    }

    /**
     * Vista de residentes por asignar
     */
    public function unassigned()
    {
        // Forzar contexto para demo
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        $condoId = $demoCondo ? (int)$demoCondo['id'] : 0;
        
        if ($condoId > 0) {
            \App\Services\TenantService::getInstance()->setTenantId($condoId);
        }

        $db = \Config\Database::connect();

        // Residentes SIN unidad asignada (unit_id IS NULL)
        $builder = $db->table('residents');
        $builder->select('residents.id as resident_id, residents.type, residents.is_active, residents.created_at as resident_since,
                          users.id as user_id, users.first_name, users.last_name, users.email, users.phone, users.avatar');
        $builder->join('users', 'users.id = residents.user_id');
        $builder->where('residents.condominium_id', $condoId);
        $builder->groupStart()
                ->where('residents.unit_id', null)
                ->orWhere('residents.unit_id', 0)
                ->groupEnd();
        $builder->orderBy('users.first_name', 'ASC');
        $unassigned = $builder->get()->getResultArray();

        // Unidades disponibles para asignar
        $unitModel = new \App\Models\Tenant\UnitModel();
        $units = [];
        if ($condoId > 0) {
            $units = $unitModel->where('condominium_id', $condoId)
                               ->orderBy('unit_number', 'ASC')
                               ->findAll();
        }

        return view('admin/unassigned_residents', [
            'unassigned' => $unassigned,
            'units' => $units
        ]);
    }

    /**
     * POST /admin/residentes/asignar-unidad (JSON)
     * Asigna una unidad a un residente existente
     */
    public function assignUnitJson()
    {
        $residentId = $this->request->getPost('resident_id');
        $unitId     = $this->request->getPost('unit_id');

        if (!$residentId || !$unitId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos.'])->setStatusCode(400);
        }

        $residentModel = new ResidentModel();
        $resident = $residentModel->find($residentId);

        if (!$resident) {
            return $this->response->setJSON(['success' => false, 'message' => 'Residente no encontrado.'])->setStatusCode(404);
        }

        $residentModel->update($residentId, ['unit_id' => $unitId]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Unidad asignada exitosamente.'
        ]);
    }

    // ==========================================
    // MANAGE RESIDENT MODAL API ENDPOINTS
    // ==========================================

    public function getProfile($userId)
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if (!$demoCondo) return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);

        $db = \Config\Database::connect();
        
        // Fetch user basic info
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) return $this->response->setJSON(['success' => false, 'message' => 'Usuario no encontrado'])->setStatusCode(404);

        // Fetch their units/roles in this condo
        $builder = $db->table('residents');
        $builder->select('residents.id as resident_id, residents.type, residents.unit_id, units.unit_number');
        $builder->join('units', 'units.id = residents.unit_id', 'left');
        $builder->where('residents.user_id', $userId);
        $builder->where('residents.condominium_id', $demoCondo['id']);
        
        $assignments = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'avatar' => $user['avatar'] ?? null,
                'status' => $user['status'],
                'created_at' => $user['created_at']
            ],
            'assignments' => $assignments
        ]);
    }

    public function changeUnitJson()
    {
        $residentId = $this->request->getPost('resident_id');
        $unitId = $this->request->getPost('unit_id');

        if (!$residentId || !$unitId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos.'])->setStatusCode(400);
        }

        $residentModel = new ResidentModel();
        if ($residentModel->update($residentId, ['unit_id' => $unitId])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Unidad actualizada.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar unidad.'])->setStatusCode(500);
    }

    public function changeRoleJson()
    {
        $residentId = $this->request->getPost('resident_id');
        $type = $this->request->getPost('role'); // 'owner' or 'tenant'

        if (!$residentId || !in_array($type, ['owner', 'tenant'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Datos inválidos.'])->setStatusCode(400);
        }

        $residentModel = new ResidentModel();
        if ($residentModel->update($residentId, ['type' => $type])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Rol actualizado.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar rol.'])->setStatusCode(500);
    }

    public function removeUnitJson()
    {
        $residentId = $this->request->getPost('resident_id');
        if (!$residentId) return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos.'])->setStatusCode(400);

        $residentModel = new ResidentModel();
        $resident = $residentModel->find($residentId);

        if (!$resident) {
            return $this->response->setJSON(['success' => false, 'message' => 'Residente no encontrado.'])->setStatusCode(404);
        }

        if ($residentModel->delete($residentId)) {
            // Verificamos si ya no le quedan asignaciones en este condominio
            $remaining = $residentModel->where('user_id', $resident['user_id'])
                                       ->where('condominium_id', $resident['condominium_id'])
                                       ->countAllResults();
            
            if ($remaining === 0) {
                // Si no tiene más unidades, se elimina su rol para revocar acceso a la PWA/App
                $db = \Config\Database::connect();
                $db->table('user_condominium_roles')
                   ->where('user_id', $resident['user_id'])
                   ->where('condominium_id', $resident['condominium_id'])
                   ->delete();
                   
                // Forzar cierre de sesión inmediato en dispositivos
                $tokenService = new \App\Services\Auth\TokenService();
                $tokenService->revokeAllUserTokens($resident['user_id']);
            }

            return $this->response->setJSON(['success' => true, 'message' => 'Unidad removida.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al remover unidad.'])->setStatusCode(500);
    }

    public function removeCommunityJson()
    {
        $userId = $this->request->getPost('user_id');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'Datos incompletos.'])->setStatusCode(400);

        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if (!$demoCondo) return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);

        $db = \Config\Database::connect();
        $db->table('residents')
           ->where('user_id', $userId)
           ->where('condominium_id', $demoCondo['id'])
           ->delete();

        // Remover rol de condominio para bloquear completamente el acceso
        $db->table('user_condominium_roles')
           ->where('user_id', $userId)
           ->where('condominium_id', $demoCondo['id'])
           ->delete();
           
        // Forzar cierre de sesión inmediato en dispositivos
        $tokenService = new \App\Services\Auth\TokenService();
        $tokenService->revokeAllUserTokens($userId);

        return $this->response->setJSON(['success' => true, 'message' => 'Residente removido de la comunidad exitosamente.']);
    }

    public function updatePhoneJson()
    {
        $userId = $this->request->getPost('user_id');
        $phone = $this->request->getPost('phone');

        if (!$userId || !$phone) {
            return $this->response->setJSON(['success' => false, 'message' => 'Faltan datos.'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('users');
        if ($builder->where('id', $userId)->update(['phone' => $phone])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Teléfono actualizado.']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar teléfono.'])->setStatusCode(500);
    }
}
