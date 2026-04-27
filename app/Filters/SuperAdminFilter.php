<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * SuperAdminFilter (RBAC)
 * 
 * Protege las rutas /superadmin asegurando que SOLO usuarios con el rol
 * SUPER_ADMIN puedan acceder. Los admins de condominio y residentes
 * serán redirigidos a su panel correspondiente.
 * 
 * Flujo:
 *  1. Verifica sesión activa (complementa AuthFilter)
 *  2. Consulta user_condominium_roles + roles para el usuario actual
 *  3. Valida que tenga role_name = 'SUPER_ADMIN' con condominium_id IS NULL
 *  4. Si no es SuperAdmin → redirect a /admin/dashboard con error
 */
class SuperAdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $userId = $session->get('user_id');

        // Seguridad: si no hay usuario en sesión, delegar al AuthFilter
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Por favor, inicie sesión.');
        }

        // Verificar si el usuario tiene el rol SUPER_ADMIN
        $db = \Config\Database::connect();
        $isSuperAdmin = $db->table('user_condominium_roles AS ucr')
            ->join('roles AS r', 'r.id = ucr.role_id')
            ->where('ucr.user_id', $userId)
            ->where('r.name', 'SUPER_ADMIN')
            ->groupStart()
                ->where('ucr.condominium_id IS NULL')
                ->orWhere('ucr.condominium_id', 0)
            ->groupEnd()
            ->countAllResults() > 0;

        if (!$isSuperAdmin) {
            log_message('warning', "[SECURITY] Acceso denegado a SuperAdmin panel: user_id={$userId}");
            return redirect()->to('/admin/dashboard')
                ->with('error', 'No tienes permisos para acceder al módulo de Super Administración.');
        }

        // SuperAdmin confirmado: setear tenant_id = 0 para bypass de BaseTenantModel
        \App\Services\TenantService::getInstance()->setTenantId(0);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No post-processing required
    }
}
