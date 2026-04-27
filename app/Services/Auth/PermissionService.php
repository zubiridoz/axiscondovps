<?php

namespace App\Services\Auth;

use App\Models\Core\RolePermissionModel;
use App\Models\Core\PermissionModel;

/**
 * PermissionService
 * 
 * Gestiona la carga de permisos asociados a un rol específico dentro de un Tenant.
 */
class PermissionService
{
    /**
     * Obtiene y plana en un array de strings los nombres de los permisos asignados a un rol.
     * Ejemplo retorno: ['manage_users', 'view_reports', 'create_tickets']
     */
    public function getPermissionsForRole(int $roleId): array
    {
        /*
         * Query equivalente a:
         * SELECT p.name 
         * FROM role_permissions rp 
         * JOIN permissions p ON rp.permission_id = p.id 
         * WHERE rp.role_id = ?
         */
        $db = \Config\Database::connect();
        $builder = $db->table('role_permissions rp');
        $builder->select('p.name');
        $builder->join('permissions p', 'p.id = rp.permission_id');
        $builder->where('rp.role_id', $roleId);
        
        $results = $builder->get()->getResultArray();
        
        // Aplastamos el array para que solo sea flat strings
        return array_column($results, 'name');
    }
    
    /**
     * Verifica si un permiso existe en el arreglo de sesión del usuario.
     */
    public function hasPermission(string $permissionName): bool
    {
        $permissions = session()->get('permissions') ?? [];
        return in_array($permissionName, $permissions);
    }
}
