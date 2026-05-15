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
        try {
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
                $contentType = $request->getHeaderLine('Content-Type');
                if ($contentType && strpos(strtolower((string)$contentType), 'application/json') !== false) {
                    try {
                        $json = $request->getJSON();
                        if ($json && is_object($json) && isset($json->email)) {
                            $email = $json->email;
                        } else if ($json && is_array($json) && isset($json['email'])) {
                            $email = $json['email'];
                        }
                    } catch (\Throwable $e) {
                        // Ignorar JSON malformado
                    }
                }
                if (empty($email)) {
                    $email = $request->getPost('email');
                }
                // MD5 asegura que no haya caracteres inválidos como '@' que rompen la caché de CI4
                $emailHash = md5(strtolower(trim((string)$email)));
                $key = "rl_{$type}_{$ip}_{$emailHash}";
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
        } catch (\Throwable $e) {
            // Fallback Crítico: Previene pantallazo rojo en Flutter asegurando respuesta JSON
            log_message('critical', '[RATE_LIMIT_ERROR] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            
            $response = Services::response();
            return $response->setJSON([
                'success' => false,
                'message' => 'Error en el filtro de seguridad. Intente nuevamente.'
            ])->setStatusCode(500);
        }
    }

    public function after(RequestInterface $request, $response, $arguments = null)
    {
        try {
            $config = new RateLimit();
            if (!$config->enabled) {
                return;
            }

            $type = $arguments[0] ?? null;
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
        } catch (\Throwable $e) {
            log_message('critical', '[RATE_LIMIT_AFTER_ERROR] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            // No retornamos 500 aquí porque la petición principal (Controller) ya fue exitosa.
            // Simplemente evitamos que falle en la post-ejecución.
        }
    }
}
