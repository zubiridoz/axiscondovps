<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\Core\UserModel;
use App\Models\Tenant\UserCondominiumRoleModel;
use App\Models\Core\RoleModel;

/**
 * UserController (SaaS)
 * 
 * Gestión global de usuarios. El SuperAdmin crea administradores
 * y los asocia a sus respectivos condominios.
 */
class UserController extends BaseController
{
    /**
     * Listar usuarios globales de la plataforma
     */
    public function index()
    {
        $userModel = new UserModel();
        // findAll con paginador es ideal aquí: $userModel->paginate(50)
        $users = $userModel->select('id, first_name, last_name, email, phone, is_active, created_at')
                           ->orderBy('created_at', 'DESC')
                           ->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $users]);
    }

    /**
     * Crear un usuario administrador del sistema cliente
     */
    public function createAdmin()
    {
        $firstName = $this->request->getPost('first_name');
        $lastName  = $this->request->getPost('last_name');
        $email     = $this->request->getPost('email');
        $password  = $this->request->getPost('password');
        
        if (empty($firstName) || empty($email) || empty($password)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Campos obligatorios faltantes']);
        }

        $userModel = new UserModel();

        // Validar si el email ya existe
        if ($userModel->where('email', $email)->first()) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El correo electrónico ya está registrado']);
        }

        $userId = $userModel->insert([
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'is_active'     => 1
        ]);

        return $this->response->setJSON([
            'status'  => 201, 
            'message' => 'Usuario Administrador creado', 
            'user_id' => $userId
        ]);
    }

    /**
     * Asignar usuario a un Condominio con un Rol específico
     */
    public function assignToCondominium()
    {
        $userId        = $this->request->getPost('user_id');
        $condominiumId = $this->request->getPost('condominium_id');
        $roleName      = $this->request->getPost('role') ?? 'ADMIN';

        if (!$userId || !$condominiumId) {
             return $this->response->setJSON(['status' => 400, 'error' => 'Faltan parámetros']);
        }

        // Obtener el Role ID
        $roleModel = new RoleModel();
        $roleInfo = $roleModel->where('name', $roleName)->first();

        if (!$roleInfo) {
             return $this->response->setJSON(['status' => 404, 'error' => 'Rol especificado no existe']);
        }

        $userRoleModel = new UserCondominiumRoleModel();
        
        // Registrar en la tabla pivot
        $userRoleModel->insert([
            'user_id'        => $userId,
            'condominium_id' => $condominiumId,
            'role_id'        => $roleInfo['id']
        ]);

        return $this->response->setJSON(['status' => 200, 'message' => "Usuario asignado al Condominio $condominiumId con rol $roleName"]);
    }

    /**
     * Bloquear temporalmente el acceso global de un usuario
     */
    public function block($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID requerido']);

        $userModel = new UserModel();
        if (!$userModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Usuario no encontrado']);
        }

        $userModel->update($id, ['is_active' => 0]);

        return $this->response->setJSON(['status' => 200, 'message' => 'Usuario BLOQUEADO globalmente']);
    }
}
