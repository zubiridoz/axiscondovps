<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\TenantService;

/**
 * TenantContextFilter
 * 
 * Este filtro es el puente final post-login que levanta la variable del Session Web
 * y la inyecta formalmente en el Singleton `TenantService` en la memoria del ciclo 
 * para que el `BaseTenantModel` lo pueda capturar.
 */
class TenantContextFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $condominiumId = $session->get('current_condominium_id');
        
        if ($condominiumId) {
            TenantService::getInstance()->setTenantId((int)$condominiumId);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
