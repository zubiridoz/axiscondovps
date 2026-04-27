<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Auth\SessionService;

/**
 * AuthFilter
 * 
 * Middleware base. Verifica que exista una sesión activa en la plataforma web.
 * Bloquea el acceso a controladores protegidos del Backend/Dashboard admin.
 */
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // 1. ¿Está intentando acceder sin estar logueado?
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/login')->with('error', 'Por favor, inicie sesión.');
        }

        // 2. ¿Tiene el Tenant activo fijado en la sesión? (Prevención Tenant Injection pasivo)
        // SuperAdmin usa condominium_id=0 (global) → permitir paso
        $sessionService = new SessionService();
        $currentCondoId = $session->get('current_condominium_id');
        if (!$sessionService->hasActiveTenant() && $currentCondoId !== 0) {
            return redirect()->to('/auth/select-tenant')->with('warning', 'Debe seleccionar un condominio para continuar.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No operations here
    }
}
