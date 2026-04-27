<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\SessionService;

class LogoutController extends BaseController
{
    /**
     * Cierra la sesión web
     */
    public function logout()
    {
        $sessionService = new SessionService();
        $sessionService->destroySession();
        
        return redirect()->to('/login')->with('success', 'Sesión terminada exitosamente.');
    }
}
