<?php

namespace App\Controllers;

use App\Models\Tenant\ResidentInvitationModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Core\UserModel;
use CodeIgniter\I18n\Time;

class PublicInvitationController extends BaseController
{
    /**
     * GET /invite/{token}
     * Pantalla pública donde el usuario ve su invitación y establece una contraseña
     */
    public function accept($token)
    {
        $db = \Config\Database::connect();
        $invitation = $db->table('resident_invitations')
                         ->where('token', $token)
                         ->where('invitation_status', 'pending')
                         ->get()->getRowArray();

        if (!$invitation) {
            return view('errors/html/error_404', ['message' => 'El enlace de invitación es inválido, ya fue usado o ha sido cancelado.']);
        }

        \App\Services\TenantService::getInstance()->setTenantId((int)$invitation['condominium_id']);
        $invitationModel = new ResidentInvitationModel();

        // Revisar expiración
        if (Time::now()->isAfter($invitation['expires_at'])) {
            $invitationModel->update($invitation['id'], ['invitation_status' => 'expired']);
            return view('errors/html/error_404', ['message' => 'El enlace de invitación ha expirado. Por favor solicita uno nuevo al administrador de tu condominio.']);
        }

        // Redirigir a la PWA con el token para auto-abrir la pestaña de registro
        return redirect()->to(base_url('pwa/resident/login.html?token=' . $token));
    }

    /**
     * POST /register-resident
     * Método puente para manejar el registro ingresando el token manualmente en el portal web (fuera de la PWA)
     */
    public function registerManual()
    {
        // --- PROTECCIÓN ANTI-BOTS (Solo Honeypot y Tiempo) ---
        if (!empty($this->request->getPost('website_url'))) {
            log_message('warning', 'Bot bloqueado (Honeypot Residente): IP=' . $this->request->getIPAddress());
            return redirect()->back()->with('error', 'Solicitud inválida.');
        }

        $formLoadedAt = $this->request->getPost('form_loaded_at');
        $elapsed = time() - (int)$formLoadedAt;
        if ($elapsed < 3 || $elapsed > 86400) {
            log_message('warning', 'Bot bloqueado (Tiempo Residente): IP=' . $this->request->getIPAddress() . ' Elapsed=' . $elapsed);
            return redirect()->back()->with('error', 'Solicitud inválida por tiempo.');
        }
        // --- FIN PROTECCIÓN ANTI-BOTS ---

        $token = $this->request->getPost('token');
        if (empty($token)) {
            return redirect()->back()->with('error', 'El código de invitación es requerido.');
        }

        return $this->register($token);
    }

    /**
     * POST /invite/{token}/register
     * Proceso donde el usuario guarda su contraseña, se crea su perfil y entra al condominio
     */
    public function register($token)
    {
        $db = \Config\Database::connect();
        $invitation = $db->table('resident_invitations')
                         ->where('token', $token)
                         ->where('invitation_status', 'pending')
                         ->get()->getRowArray();

        if (!$invitation) {
            return redirect()->to('/login')->with('error', 'Invitación inválida o expirada.');
        }

        \App\Services\TenantService::getInstance()->setTenantId((int)$invitation['condominium_id']);
        
        if (Time::now()->isAfter($invitation['expires_at'])) {
            $invitationModel = new ResidentInvitationModel();
            $invitationModel->update($invitation['id'], ['invitation_status' => 'expired']);
            return redirect()->to('/login')->with('error', 'Invitación inválida o expirada.');
        }

        $userModel = new UserModel();
        // Check if user already exists
        $existingUser = $userModel->where('email', $invitation['email'])->first();

        $password = $this->request->getPost('password');
        
        // Solo obligar contraseña si el usuario NO existe
        if (!$existingUser) {
            if (empty($password) || strlen($password) < 6) {
                return redirect()->back()->with('error', 'La contraseña debe tener al menos 6 caracteres.');
            }
        }
        
        try {
            $db->transStart();

            // 1. Crear usuario en Core
            if ($existingUser) {
                $userId = $existingUser['id'];
            } else {
                // Get first/last name from string loosely
                $nameParts = explode(' ', $invitation['name'], 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $userId = $userModel->insert([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $invitation['email'],
                    'phone' => $invitation['phone'],
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'status' => 'active'
                ]);
            }

            // 2. Crear Resident en el tenant (Si no estaba ya en ese piso/condominio)
            $residentModel = new ResidentModel();
            $existingResident = $residentModel->where('user_id', $userId)
                                              ->where('condominium_id', $invitation['condominium_id'])
                                              ->first();
            
            if (!$existingResident) {
                // Here we disable Tenant Context or force it, since this is a public endpoint
                // but BaseTenantModel might require it. We bypass standard validation array manually if needed:
                $residentData = [
                    'condominium_id' => $invitation['condominium_id'],
                    'user_id' => $userId,
                    'unit_id' => $invitation['unit_id'] ?? null,
                    'type' => $invitation['role'] === 'owner' ? 'owner' : 'tenant',
                    'is_active' => 1
                ];
                
                $db->table('residents')->insert($residentData);
            }

            // 2b. Asegurar el pivot user_condominium_roles (necesario para LoginService)
            $existingPivot = $db->table('user_condominium_roles')
                ->where('user_id', $userId)
                ->where('condominium_id', $invitation['condominium_id'])
                ->get()->getRow();
            
            if (!$existingPivot) {
                // Buscar el role_id de RESIDENT (normalmente id=3)
                $residentRole = $db->table('roles')->where('name', 'RESIDENT')->get()->getRow();
                $roleId = $residentRole ? $residentRole->id : 3;
                
                $db->table('user_condominium_roles')->insert([
                    'user_id' => $userId,
                    'condominium_id' => $invitation['condominium_id'],
                    'role_id' => $roleId
                ]);
            }

            // 3. Actualizar la invitación a aceptada
            $db->table('resident_invitations')->where('id', $invitation['id'])->update([
                'invitation_status' => 'accepted',
                'accepted_at' => Time::now()->format('Y-m-d H:i:s')
            ]);

            // 3b. Notificar a los administradores del condominio
            $unitNumber = 'Sin unidad';
            if (!empty($invitation['unit_id'])) {
                $unitRow = $db->table('units')->select('unit_number')->where('id', $invitation['unit_id'])->get()->getRowArray();
                if ($unitRow) {
                    $unitNumber = $unitRow['unit_number'];
                }
            }

            $admins = $db->table('user_condominium_roles')
                ->where('condominium_id', $invitation['condominium_id'])
                ->where('role_id', 2) // ADMIN
                ->get()
                ->getResultArray();

            foreach ($admins as $admin) {
                try {
                    \App\Models\Tenant\NotificationModel::notify(
                        (int) $invitation['condominium_id'],
                        (int) $admin['user_id'],
                        'resident_joined',
                        'Residente Registrado',
                        "{$invitation['name']} se ha registrado en la unidad {$unitNumber}.",
                        [],
                        false
                    );
                } catch (\Throwable $e) {
                    log_message('error', "Error al notificar admin {$admin['user_id']}: " . $e->getMessage());
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return redirect()->back()->with('error', 'Error al procesar el registro.');
            }

            return redirect()->to('/app-required')->with('success', '¡Registro exitoso! Descarga la app para acceder a tu comunidad.');

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al procesar tu cuenta.');
        }
    }

    /**
     * POST /api/v1/register-invitation (JSON API para PWA)
     * Misma lógica que register() pero con respuesta JSON para la PWA
     */
    public function registerApi()
    {
        $json = $this->request->getJSON(true);
        $token = $json['token'] ?? '';
        $password = $json['password'] ?? '';
        $passwordConfirm = $json['password_confirm'] ?? '';

        if (empty($token)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El código de invitación es requerido.'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $invitation = $db->table('resident_invitations')
                         ->where('token', $token)
                         ->where('invitation_status', 'pending')
                         ->get()->getRowArray();

        if (!$invitation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Código de invitación inválido o ya fue usado.'])->setStatusCode(404);
        }

        \App\Services\TenantService::getInstance()->setTenantId((int)$invitation['condominium_id']);

        if (Time::now()->isAfter($invitation['expires_at'])) {
            $invitationModel = new ResidentInvitationModel();
            $invitationModel->update($invitation['id'], ['invitation_status' => 'expired']);
            return $this->response->setJSON(['success' => false, 'message' => 'La invitación ha expirado. Solicita una nueva a tu administrador.'])->setStatusCode(410);
        }

        // Comprobar si el usuario ya existe
        $userModel = new UserModel();
        $existingUser = $userModel->where('email', $invitation['email'])->first();

        // Solo obligar contraseña si el usuario NO existe
        if (!$existingUser) {
            if (empty($password) || strlen($password) < 6) {
                return $this->response->setJSON(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres.'])->setStatusCode(400);
            }

            if ($password !== $passwordConfirm) {
                return $this->response->setJSON(['success' => false, 'message' => 'Las contraseñas no coinciden.'])->setStatusCode(400);
            }
        }

        try {
            $db->transStart();

            // 1. Crear o encontrar usuario
            if ($existingUser) {
                $userId = $existingUser['id'];
            } else {
                $nameParts = explode(' ', $invitation['name'], 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $userId = $userModel->insert([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $invitation['email'],
                    'phone' => $invitation['phone'],
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'status' => 'active'
                ]);
            }

            // 2. Crear Resident
            $existingResident = $db->table('residents')
                ->where('user_id', $userId)
                ->where('condominium_id', $invitation['condominium_id'])
                ->get()->getRow();

            if (!$existingResident) {
                $db->table('residents')->insert([
                    'condominium_id' => $invitation['condominium_id'],
                    'user_id' => $userId,
                    'unit_id' => $invitation['unit_id'] ?? null,
                    'type' => ($invitation['role'] ?? 'tenant') === 'owner' ? 'owner' : 'tenant',
                    'is_active' => 1
                ]);
            }

            // 3. Asegurar user_condominium_roles
            $existingPivot = $db->table('user_condominium_roles')
                ->where('user_id', $userId)
                ->where('condominium_id', $invitation['condominium_id'])
                ->get()->getRow();

            if (!$existingPivot) {
                $residentRole = $db->table('roles')->where('name', 'RESIDENT')->get()->getRow();
                $roleId = $residentRole ? $residentRole->id : 3;

                $db->table('user_condominium_roles')->insert([
                    'user_id' => $userId,
                    'condominium_id' => $invitation['condominium_id'],
                    'role_id' => $roleId
                ]);
            }

            // 4. Actualizar invitación
            $db->table('resident_invitations')->where('id', $invitation['id'])->update([
                'invitation_status' => 'accepted',
                'accepted_at' => Time::now()->format('Y-m-d H:i:s')
            ]);

            // 4b. Notificar a los administradores del condominio
            $unitNumber = 'Sin unidad';
            if (!empty($invitation['unit_id'])) {
                $unitRow = $db->table('units')->select('unit_number')->where('id', $invitation['unit_id'])->get()->getRowArray();
                if ($unitRow) {
                    $unitNumber = $unitRow['unit_number'];
                }
            }

            $admins = $db->table('user_condominium_roles')
                ->where('condominium_id', $invitation['condominium_id'])
                ->where('role_id', 2) // ADMIN
                ->get()
                ->getResultArray();

            foreach ($admins as $admin) {
                try {
                    \App\Models\Tenant\NotificationModel::notify(
                        (int) $invitation['condominium_id'],
                        (int) $admin['user_id'],
                        'resident_joined',
                        'Residente Registrado',
                        "{$invitation['name']} se ha registrado en la unidad {$unitNumber}.",
                        [],
                        false
                    );
                } catch (\Throwable $e) {
                    log_message('error', "Error al notificar admin {$admin['user_id']}: " . $e->getMessage());
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al procesar el registro.'])->setStatusCode(500);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => '¡Registro exitoso! Descarga la app para acceder a tu comunidad.',
                'data' => ['email' => $invitation['email']],
                'redirect_url' => base_url('app-required')
            ]);

        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error inesperado: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * GET /api/v1/invitation/validate
     * Valida el token de invitación y verifica si el correo ya está registrado en el sistema.
     */
    public function validateToken()
    {
        $token = $this->request->getGet('token');
        if (empty($token)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Token requerido.'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $invitation = $db->table('resident_invitations')
                         ->where('token', $token)
                         ->where('invitation_status', 'pending')
                         ->get()->getRowArray();

        if (!$invitation) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invitación no válida o expirada.'])->setStatusCode(404);
        }

        // Obtener nombre del condominio
        $condo = $db->table('condominiums')->select('name')->where('id', $invitation['condominium_id'])->get()->getRowArray();
        $condoName = $condo ? $condo['name'] : 'Mi Condominio';

        // Verificar si el usuario ya tiene cuenta registrada
        $userModel = new UserModel();
        $existingUser = $userModel->where('email', $invitation['email'])->first();

        return $this->response->setJSON([
            'success'          => true,
            'email'            => $invitation['email'],
            'user_exists'      => $existingUser ? true : false,
            'condominium_name' => $condoName
        ]);
    }

    /**
     * GET /api/v1/invitation/check-resident-email
     * Comprueba si un correo tiene una invitación de residente o ya es residente.
     */
    public function checkResidentEmail()
    {
        $email = $this->request->getGet('email');
        if (empty($email)) {
            return $this->response->setJSON(['has_invitation' => false]);
        }

        $db = \Config\Database::connect();

        // 1. Buscar si tiene invitación de residente
        $invitation = $db->table('resident_invitations')
                         ->where('email', $email)
                         ->get()->getRowArray();

        // 2. Buscar si ya es usuario registrado con rol de residente
        $hasResidentRole = false;
        $user = $db->table('users')->where('email', $email)->get()->getRowArray();
        if ($user) {
            $role = $db->table('user_condominium_roles')
                       ->where('user_id', $user['id'])
                       ->where('role_id', 3) // RESIDENT
                       ->get()->getRowArray();
            if ($role) {
                $hasResidentRole = true;
            }
        }

        return $this->response->setJSON([
            'has_invitation' => ($invitation || $hasResidentRole) ? true : false
        ]);
    }
}
