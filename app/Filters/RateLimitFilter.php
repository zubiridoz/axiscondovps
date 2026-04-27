<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * RateLimitFilter
 * 
 * Protege a AXISCONDO de ataques de fuerza bruta (Bruteforce) y denegación 
 * de servicio (DDoS) controlando el flujo por Perfiles (Throttling).
 */
class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();
        
        // Identificar Perfil de Límite (Basado en el argumento en Filters.php)
        // Ej: 'rate_limit:login'
        $profile = $arguments[0] ?? 'default';

        $ip = $request->getIPAddress();

        // Configuración Dinámica Multi-Perfil
        switch ($profile) {
            case 'login':
                $requestsPerMinute = 5;     // 5 intentos por minuto (Fuerza Bruta Login)
                $bucketName = "auth_{$ip}";
                break;
            case 'qr':
                $requestsPerMinute = 60;    // 60 lecturas/min (Caseta Scanner Guard)
                $bucketName = "qr_validator_{$ip}";
                break;
            case 'default':
            default:
                $requestsPerMinute = 100;   // General API PWA Endpoints
                $bucketName = "api_{$ip}";
                break;
        }

        // El Throttler de CI4 evalúa los tokens
        if ($throttler->check($bucketName, $requestsPerMinute, MINUTE) === false) {
            
            // HTTP 429 Too Many Requests
            return Services::response()
                ->setStatusCode(429)
                ->setJSON([
                    'status'  => 'error', 
                    'message' => 'Límite de peticiones excedido. Intente más tarde.',
                    'retry_after' => 60 // Segundos 
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere procesamiento posterior
    }
}
