<?php

namespace App\Services\Auth;

/**
 * SessionService
 * 
 * Abstrae la manipulación de la sesión web nativa de CodeIgniter,
 * guardando el contexto de seguridad y el tenant list.
 */
class SessionService
{
    /**
     * Crea la sesión inicial tras validar credenciales.
     * Guarda el ID del usuario y los tenants a los que tiene acceso.
     */
    public function createInitialSession(array $user, array $tenantRoles): void
    {
        $session = session();
        
        // Extraemos solo los IDs de los condominios a los que pertenece para protección Tenant Injection
        $allowedTenants = array_column($tenantRoles, 'condominium_id');
        
        $sessionData = [
            'is_logged_in'    => true,
            'user_id'         => $user['id'],
            'user_name'       => $user['first_name'] . ' ' . $user['last_name'],
            'user_email'      => $user['email'],
            'allowed_tenants' => $allowedTenants,
            // Aún no definimos el tenant actual ni los permisos hasta que el usuario elija
            'current_condominium_id' => null, 
            'current_role_id'        => null,
            'permissions'            => []
        ];
        
        $session->set($sessionData);
    }

    /**
     * Fija un condominio como activo y carga sus permisos.
     * PROTECCIÓN CRÍTICA: Lanza excepción si intenta inyectar un tenant no permitido.
     */
    public function setTenantContext(int $condominiumId, int $roleId, array $permissions): void
    {
        $session = session();
        $allowedTenants = $session->get('allowed_tenants') ?? [];
        
        // Verificación Prevención Tenant Injection
        if (!in_array($condominiumId, $allowedTenants)) {
            throw new \Exception('Acceso Prohibido (403): Intento de inyección de Tenant.', 403);
        }
        
        $session->set([
            'current_condominium_id' => $condominiumId,
            'condominium_id'         => $condominiumId,
            'current_role_id'        => $roleId,
            'permissions'            => $permissions
        ]);

        // Cargar flag is_owner para el tenant activo (permisos de fundador)
        $db = \Config\Database::connect();
        $ownerFlag = $db->table('user_condominium_roles')
            ->where('user_id', $session->get('user_id'))
            ->where('condominium_id', $condominiumId)
            ->where('role_id', 2) // ADMIN
            ->get()->getRowArray();

        $session->set('is_owner', (int)($ownerFlag['is_owner'] ?? 0));
    }

    /**
     * Destruye la sesión de forma segura.
     */
    public function destroySession(): void
    {
        session()->destroy();
    }
    
    /**
     * Verifica si la sesión tiene un tenant activo.
     */
    public function hasActiveTenant(): bool
    {
        return session()->has('current_condominium_id') && session()->get('current_condominium_id') !== null;
    }
}
