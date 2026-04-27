<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\LoginService;
use App\Services\Auth\SessionService;

class AuthController extends BaseController
{
    /**
     * Muestra la vista de login web
     */
    public function login()
    {
        // Si ya está logueado, lo mandamos al dashboard o selector
        if (session()->get('is_logged_in')) {
            // TODO: Podría validarse si es superadmin vs admin mediante rol
            return redirect()->to('/admin/dashboard');
        }
        
        // Renderizar vista CI4 para login web unificado
        return view('auth/login');
    }

    /**
     * Cortafuego de validación post-login web (Form Submit)
     */
    public function attemptLogin()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $loginService   = new LoginService();
        $sessionService = new SessionService();
        
        try {
            // Validamos credenciales core
            $user = $loginService->validateCredentials($email, $password);
            
            if (!$user) {
                return redirect()->back()->with('error', 'Credenciales incorrectas');
            }
            
            // Buscamos a qué condominios pertenece el empleado/vecino
            $tenantRoles = $loginService->getUserTenants($user['id']);
            
            if (empty($tenantRoles)) {
                return redirect()->back()->with('error', 'Su usuario no está asignado a ningún condominio. Contacte a soporte.');
            }
            
            // Bloqueo de PWA para Residentes y Guardias (UX Bancaria - Requerir App)
            $canAccessWeb = false;
            foreach ($tenantRoles as $role) {
                if (!in_array(strtoupper($role['role_name']), ['RESIDENT', 'GUARD', 'SECURITY'])) {
                    $canAccessWeb = true;
                    break;
                }
            }
            
            if (!$canAccessWeb) {
                // Tienen rol de residente o guardia EXCLUSIVAMENTE, no los dejamos entrar a web
                return redirect()->to('/app-required');
            }
            
            // Instanciar Sesión inicial (Guardar allowed_tenants para blindaje JWT-like)
            $sessionService->createInitialSession($user, $tenantRoles);
            
            // Si el usuario pertenece a 1 solo condominio -> Entrada VIP Directa
            if (count($tenantRoles) === 1) {
                return redirect()->to('/auth/select-tenant/' . $tenantRoles[0]['condominium_id']);
            }
            
            // Si tiene múltiples (+1), derivarlo a la Selección Condominal
            return redirect()->to('/auth/select-tenant');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Endpoint para seleccionar el tenant de la sesión (o asignarlo automáticamente)
     */
    public function selectTenant($condominiumId = null)
    {
        if ($condominiumId === null || $condominiumId === '') {
            $loginService = new \App\Services\Auth\LoginService();
            $fullTenants = $loginService->getUserTenants(session()->get('user_id'));
            
            // Placeholder: Vista de selección de condominios (Multi-tenant manual)
            return view('auth/select_tenant', ['tenants' => $fullTenants]);
        }
        
        $sessionService = new SessionService();
        $loginService   = new LoginService();
        
        try {
            $userId = session()->get('user_id');
            if (!$userId) return redirect()->to('/login');

            $tenantRoles = $loginService->getUserTenants($userId);
            $roleId = null;
            $roleName = null;
            foreach ($tenantRoles as $tr) {
                if ($tr['condominium_id'] == $condominiumId || ($tr['condominium_id'] === null && $tr['role_name'] === 'SUPER_ADMIN')) {
                    $roleId = $tr['role_id'];
                    $roleName = $tr['role_name'];
                    break;
                }
            }
            
            if (!$roleId) throw new \Exception('Acceso Denegado al Condominio.');
            
            // Simulamos los permisos para el rol 
            $permissions = [];
            
            // SuperAdmin global (condominiumId=0): set session directly without tenant injection check
            if ((int)$condominiumId === 0 && strtoupper($roleName) === 'SUPER_ADMIN') {
                session()->set([
                    'current_condominium_id' => 0,
                    'condominium_id'         => 0,
                    'current_role_id'        => (int)$roleId,
                    'permissions'            => $permissions
                ]);
            } else {
                // Fija el tenant e inyecta permisos
                $sessionService->setTenantContext((int)$condominiumId, (int)$roleId, $permissions);
            }
            
            // Redirigir según rol general
            $blockedWebRoles = ['RESIDENT', 'GUARD', 'SECURITY'];
            if (in_array(strtoupper($roleName), $blockedWebRoles)) {
                $sessionService->destroySession();
                return redirect()->to('/app-required');
            }
            
            // SuperAdmin → redirigir al panel SaaS
            if (strtoupper($roleName) === 'SUPER_ADMIN') {
                return redirect()->to('/superadmin/dashboard');
            }
            
            return redirect()->to('/admin/dashboard');
            
        } catch (\Exception $e) {
            return redirect()->to('/login')->with('error', $e->getMessage());
        }
    }

    /**
     * Muestra la pantalla de descarga obligatoria de App Móvil para Residentes y Guardias
     */
    public function appRequired()
    {
        return view('auth/app_required');
    }
}
