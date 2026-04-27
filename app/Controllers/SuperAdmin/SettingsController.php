<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\Core\UserModel;

/**
 * SettingsController (SuperAdmin)
 * 
 * Gestión de perfil del SuperAdmin y administración de otros SuperAdmins.
 * Separado del SettingsController de Admin para evitar conflictos de tenant.
 */
class SettingsController extends BaseController
{
    /**
     * Vista principal de configuración del SuperAdmin.
     */
    public function index()
    {
        $userModel = new UserModel();
        $me = $userModel->find(session()->get('user_id')) ?? [];

        return view('superadmin/settings', [
            'me' => $me
        ]);
    }

    /**
     * Actualizar perfil (nombre, email).
     */
    public function updateProfile()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);
        }

        $firstName = trim((string) $this->request->getPost('first_name'));
        $lastName  = trim((string) $this->request->getPost('last_name'));
        $email     = trim((string) $this->request->getPost('email'));

        if ($firstName === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email inválido.'])->setStatusCode(422);
        }

        $userModel = new UserModel();

        // Verificar que el email no esté en uso por otro usuario
        $existing = $userModel->where('email', $email)->where('id !=', $userId)->first();
        if ($existing) {
            return $this->response->setJSON(['success' => false, 'message' => 'El email ya está en uso por otro usuario.'])->setStatusCode(409);
        }

        $userModel->update($userId, [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'email'      => $email,
        ]);

        // Actualizar datos de sesión
        session()->set('user_name', $firstName . ' ' . $lastName);
        session()->set('user_email', $email);

        return $this->response->setJSON(['success' => true, 'message' => 'Perfil actualizado correctamente.']);
    }

    /**
     * Cambiar contraseña.
     */
    public function updatePassword()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);
        }

        $currentPwd = (string) $this->request->getPost('current_password');
        $newPwd     = (string) $this->request->getPost('new_password');
        $confirmPwd = (string) $this->request->getPost('confirm_password');

        if (strlen($newPwd) < 8) {
            return $this->response->setJSON(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 8 caracteres.'])->setStatusCode(422);
        }
        if ($newPwd !== $confirmPwd) {
            return $this->response->setJSON(['success' => false, 'message' => 'La confirmación no coincide.'])->setStatusCode(422);
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!password_verify($currentPwd, $user['password_hash'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta.'])->setStatusCode(422);
        }

        $userModel->update($userId, [
            'password_hash' => password_hash($newPwd, PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Contraseña actualizada correctamente.']);
    }

    /**
     * Subir avatar.
     */
    public function uploadAvatar()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);
        }

        $file = $this->request->getFile('avatar');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo inválido.'])->setStatusCode(422);
        }

        $uploadPath = WRITEPATH . 'uploads/avatars/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        // Eliminar avatar anterior
        if (!empty($user['avatar'])) {
            $oldPath = $uploadPath . $user['avatar'];
            if (is_file($oldPath)) unlink($oldPath);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $userModel->update($userId, ['avatar' => $newName]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Avatar actualizado.',
            'url'     => base_url('superadmin/settings/avatar/' . $newName)
        ]);
    }

    /**
     * Servir avatar.
     */
    public function serveAvatar($filename)
    {
        $fullPath = WRITEPATH . 'uploads/avatars/' . $filename;
        if (!is_file($fullPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return $this->response
            ->setHeader('Content-Type', mime_content_type($fullPath))
            ->setHeader('Cache-Control', 'public, max-age=31536000')
            ->setBody(file_get_contents($fullPath));
    }

    /**
     * Listar SuperAdmins.
     */
    public function listAdmins()
    {
        $db = \Config\Database::connect();
        $admins = $db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.avatar, u.created_at,
                   ucr.id AS assignment_id
            FROM user_condominium_roles ucr
            INNER JOIN users u ON u.id = ucr.user_id
            INNER JOIN roles r ON r.id = ucr.role_id
            WHERE r.name = 'SUPER_ADMIN'
            ORDER BY ucr.created_at ASC
        ")->getResultArray();

        return $this->response->setJSON(['success' => true, 'admins' => $admins]);
    }

    /**
     * Agregar nuevo SuperAdmin.
     */
    public function addAdmin()
    {
        $email     = trim((string) $this->request->getPost('email'));
        $firstName = trim((string) $this->request->getPost('first_name'));
        $lastName  = trim((string) $this->request->getPost('last_name'));
        $password  = (string) $this->request->getPost('password');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email inválido.'])->setStatusCode(422);
        }
        if ($firstName === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }
        if (strlen($password) < 8) {
            return $this->response->setJSON(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.'])->setStatusCode(422);
        }

        $userModel = new UserModel();
        $db = \Config\Database::connect();

        // Obtener role_id de SUPER_ADMIN
        $saRole = $db->table('roles')->where('name', 'SUPER_ADMIN')->get()->getRow();
        if (!$saRole) {
            return $this->response->setJSON(['success' => false, 'message' => 'Rol SUPER_ADMIN no encontrado.'])->setStatusCode(500);
        }

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Verificar si ya es SuperAdmin
            $exists = $db->table('user_condominium_roles')
                ->where('user_id', $user['id'])
                ->where('role_id', $saRole->id)
                ->countAllResults();
            if ($exists > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Este usuario ya es Super Administrador.'])->setStatusCode(409);
            }
            $userId = $user['id'];
        } else {
            // Crear nuevo usuario
            $userId = $userModel->insert([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'status'        => 'active',
            ]);
        }

        // Asignar rol SUPER_ADMIN (condominium_id = NULL)
        $db->table('user_condominium_roles')->insert([
            'user_id'        => $userId,
            'condominium_id' => null,
            'role_id'        => $saRole->id,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Super Administrador agregado exitosamente.']);
    }

    /**
     * Remover SuperAdmin.
     */
    public function removeAdmin()
    {
        $assignmentId = (int) $this->request->getPost('assignment_id');
        $currentUserId = session()->get('user_id');

        if ($assignmentId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID inválido.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();
        $saRole = $db->table('roles')->where('name', 'SUPER_ADMIN')->get()->getRow();

        // Verificar que el assignment existe y es SuperAdmin
        $record = $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->where('role_id', $saRole->id)
            ->get()->getRowArray();

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Registro no encontrado.'])->setStatusCode(404);
        }

        // No permitir eliminarse a sí mismo
        if ((int) $record['user_id'] === (int) $currentUserId) {
            return $this->response->setJSON(['success' => false, 'message' => 'No puedes eliminarte a ti mismo como SuperAdmin.'])->setStatusCode(422);
        }

        // No permitir eliminar al último SuperAdmin
        $count = $db->table('user_condominium_roles')
            ->where('role_id', $saRole->id)
            ->countAllResults();
        if ($count <= 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'No puedes eliminar al último Super Administrador.'])->setStatusCode(422);
        }

        $db->table('user_condominium_roles')->where('id', $assignmentId)->delete();

        return $this->response->setJSON(['success' => true, 'message' => 'Super Administrador eliminado.']);
    }
}
