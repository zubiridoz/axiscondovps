<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Config\RateLimit;

/**
 * RateLimitFilter
 * Filtro de limitación de tasa para endpoints críticos orientados a la App Móvil.
 * Maneja modos de observación (logging) y bloqueo activo (429).
 */
class RateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $config = new RateLimit();
        if (!$config->enabled) {
            return;
        }

        $type = $arguments[0] ?? 'default';
        if (!isset($config->limits[$type])) {
            return; // Sin límite configurado
        }

        $capacity = $config->limits[$type][0];
        $seconds  = $config->limits[$type][1];

        // Determinar clave base (Evitar bloqueos cruzados en NAT/CGNAT usando UserID si está disponible)
        $ip = $request->getIPAddress();
        $userId = $request->userId ?? null; // Puede estar inyectado por ApiAuthFilter previo
        $condoId = $request->getHeaderLine('X-Condo-Id');

        $key = '';
        if ($type === 'login') {
            // Login requiere IP y un identificador como Email
            $email = '';
            if ($request->getJSON()) {
                $email = $request->getJSON()->email ?? '';
            } else {
                $email = $request->getPost('email') ?? '';
            }
            $emailClean = strtolower(trim($email));
            $key = "rl_{$type}_{$ip}_{$emailClean}";
        } else {
            // Demás endpoints asumen contexto autenticado
            if ($userId) {
                $key = "rl_{$type}_user_{$userId}";
            } else {
                $key = "rl_{$type}_ip_{$ip}";
            }
        }

        $throttler = Services::throttler();
        $allowed = $throttler->check($key, $capacity, $seconds);
        
        // Inyectamos estado en el request para logs detallados en el after()
        $request->rateLimitKey = $key;
        $request->rateLimitType = $type;

        if (!$allowed) {
            $userAgentObj = $request->getUserAgent();
            $logData = [
                'event' => 'rate_limit_exceeded',
                'endpoint' => $request->getUri()->getPath(),
                'type' => $type,
                'key' => $key,
                'user_id' => $userId,
                'condo_id' => $condoId,
                'ip' => $ip,
                'user_agent' => $userAgentObj ? $userAgentObj->getAgentString() : 'Unknown',
                'limit' => $capacity,
                'window_seconds' => $seconds,
                'timestamp' => date('Y-m-d H:i:s'),
                'mode' => $config->observationMode ? 'observation_only' : 'blocking'
            ];
            
            // Log estructurado (Fase 1: Observabilidad Obligatoria)
            log_message('warning', '[RATE_LIMIT] ' . json_encode($logData));

            // Si NO estamos en modo observación, bloqueamos explícitamente
            if (!$config->observationMode) {
                $response = Services::response();
                $response->setStatusCode(429);
                
                // Cabeceras sugeridas para manejo futuro en Flutter (Retry-After, etc)
                $response->setHeader('Retry-After', (string)$seconds);
                $response->setHeader('X-RateLimit-Limit', (string)$capacity);
                $response->setHeader('X-RateLimit-Remaining', '0');
                
                return $response->setJSON([
                    'success' => false,
                    'message' => 'Demasiadas peticiones. Por favor, intente más tarde.'
                ]);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $config = new RateLimit();
        if (!$config->enabled) {
            return;
        }

        $type = $request->rateLimitType ?? ($arguments[0] ?? null);
        $statusCode = $response->getStatusCode();

        // Si fue una petición exitosa y bloqueamos activo, enviamos las cabeceras restantes
        if ($type && !$config->observationMode && $statusCode < 400) {
            if (isset($config->limits[$type])) {
                $response->setHeader('X-RateLimit-Limit', (string)$config->limits[$type][0]);
                // Nota: CI4 no expone remaining tokens fácilmente, esto es decorativo
            }
        }

        // Logging detallado exclusivo para Uploads (Payments)
        if ($type === 'uploads') {
            // Identificar si la subida falló por timeout o 5xx
            if ($statusCode >= 500 || $statusCode === 408) {
                // "excluir uploads fallidos por timeout"
                // El Throttler ya se incrementó en el before(). En un esquema ideal se restaría aquí,
                // pero por ahora solo documentamos que fue fallido y no de abuso malicioso.
                $logData = [
                    'event' => 'upload_failed',
                    'reason' => 'Server error or timeout',
                    'status_code' => $statusCode,
                    'endpoint' => $request->getUri()->getPath(),
                    'user_id' => $request->userId ?? 'guest',
                    'ip' => $request->getIPAddress()
                ];
                log_message('error', '[RATE_LIMIT_UPLOAD_FAIL] ' . json_encode($logData));
                return;
            }

            // Registrar tamaño del archivo
            $file = current($request->getFiles());
            if ($file && $file->isValid()) {
                $size = $file->getSize();
                $logData = [
                    'event' => 'upload_tracked',
                    'endpoint' => $request->getUri()->getPath(),
                    'user_id' => $request->userId ?? 'guest',
                    'file_size_bytes' => $size,
                    'status_code' => $statusCode,
                    'ip' => $request->getIPAddress()
                ];
                log_message('info', '[RATE_LIMIT_UPLOAD] ' . json_encode($logData));
            }
        }
    }
}
