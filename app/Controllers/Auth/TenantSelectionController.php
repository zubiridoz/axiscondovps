<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\SessionService;
use App\Services\Auth\PermissionService;
use App\Services\Auth\LoginService;

class TenantSelectionController extends BaseController
{
    /**
     * Muestra la pantalla para elegir qué edificio administrar/ver
     */
    public function showTenants()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }
        
        $userId = session()->get('user_id');
        $loginService = new LoginService();
        
        // Obtenemos los data de los roles y podemos mandar a la vista los IDs y Nombres
        $tenants = $loginService->getUserTenants($userId); 
        
        // Aquí cargarías la vista de select tenant
        return "Vista HTML de Selección de Condominio (Mostrando " . count($tenants) . " condominios)";
    }

    /**
     * Procesa la elección del edificio y carga permisos del rol
     */
    public function selectTenant($condominiumId = null)
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to('/login');
        }
        
        // Si viene por POST
        if (!$condominiumId) {
            $condominiumId = $this->request->getPost('condominium_id');
        }
        
        $userId = session()->get('user_id');
        
        // Necesitamos encontrar qué `role_id` juega este usuario en *este* condominio específico
        $loginService = new LoginService();
        $roles        = $loginService->getUserTenants($userId);
        
        $roleId = null;
        foreach ($roles as $role) {
            if ($role['condominium_id'] == $condominiumId) {
                $roleId = $role['role_id'];
                break;
            }
        }
        
        if (!$roleId) {
            return redirect()->back()->with('error', 'No perteneces a este condominio');
        }
        
        try {
            // Cargar árbol de permisos planos del rol
            $permissionService = new PermissionService();
            $permissions       = $permissionService->getPermissionsForRole($roleId);
            
            // Tratar de anclar el tenant en la sesión (Falla si el ID inyectado no está en allowed_tenants)
            $sessionService = new SessionService();
            $sessionService->setTenantContext((int)$condominiumId, (int)$roleId, $permissions);
            
            return redirect()->to('/dashboard'); // YAY!
            
        } catch (\Exception $e) {
            // Cazamos ForbbidenException (Tenant Injection Catch)
            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }
}
