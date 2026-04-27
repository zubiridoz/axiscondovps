<?php

namespace App\Services\Auth;

use App\Models\Core\UserModel;
use App\Models\Tenant\UserCondominiumRoleModel;

/**
 * LoginService
 * 
 * Se encarga exclusivamente de validar credenciales físicas 
 * (email, password y estatus) y de localizar los tenants a los 
 * que el usuario tiene acceso.
 */
class LoginService
{
    /**
     * Intenta autenticar a un usuario con email y password.
     * Retorna el arreglo del usuario si es exitoso, o null/falso.
     */
    public function validateCredentials(string $email, string $password): ?array
    {
        $userModel = new UserModel();
        
        // 1. Buscar usuario por email
        $user = $userModel->where('email', $email)->first();
        
        if (!$user) {
            return null; // Correo no existe
        }
        
        // 2. Verificar estatus
        if ($user['status'] !== 'active') {
            throw new \Exception('La cuenta no está activa. Estatus: ' . $user['status']);
        }
        
        // 3. Verificar contraseña
        if (!password_verify($password, $user['password_hash'])) {
            return null; // Contraseña incorrecta
        }
        
        return $user;
    }

    /**
     * Busca todos los condominios a los que pertenece un usuario.
     */
    public function getUserTenants(int $userId): array
    {
        $roleModel = new UserCondominiumRoleModel();
        
        // En este punto aún no hemos seteado el Tenant activo (porque apenas estamos haciendo login),
        // por lo que debemos hacer bypass al scope del Tenant (que normalmente fuerza condominium_id = actual).
        // En CI4 podemos obtener la base del builder directamente o instanciar temporalmente esquivando los callbacks.
        
        $db = \Config\Database::connect();
        $builder = $db->table('user_condominium_roles');
        $builder->select('user_condominium_roles.condominium_id, user_condominium_roles.role_id, roles.name as role_name, condominiums.name as condominium_name, condominiums.logo');
        $builder->join('roles', 'roles.id = user_condominium_roles.role_id', 'left');
        $builder->join('condominiums', 'condominiums.id = user_condominium_roles.condominium_id', 'left');
        $builder->where('user_condominium_roles.user_id', $userId);
        $builder->groupStart()
                ->where('condominiums.deleted_at IS NULL')
                ->orWhere('user_condominium_roles.condominium_id', 0)
                ->groupEnd();
        $roles = $builder->get()->getResultArray();
        
        $finalRoles = [];
        foreach ($roles as $r) {
            // Hardening: Si el usuario es un RESIDENTE, debe tener registro en la tabla `residents`
            if (strtoupper($r['role_name']) === 'RESIDENT') {
                $hasResidentRecord = $db->table('residents')
                    ->where('user_id', $userId)
                    ->where('condominium_id', $r['condominium_id'])
                    ->countAllResults();

                if ($hasResidentRecord === 0) {
                    continue; // Saltar, es un registro huérfano
                }
            }
            $finalRoles[] = $r;
        }
        
        return $finalRoles;
    }
}
