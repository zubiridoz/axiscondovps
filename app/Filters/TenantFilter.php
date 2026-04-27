<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\TenantService;

/**
 * TenantFilter
 * 
 * Intercepta la petición HTTP y busca identificar en qué condominio estamos trabajando.
 * Puede leer desde la sesión web o un Header/Token de la PWA.
 */
class TenantFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tenantId = TenantService::getInstance()->getTenantId();

        // 1. Compatibilidad PWA/API: Buscar el tenant dictado por un header custom o por el token descifrado de autorización
        if (!$tenantId) {
            $headerTenant = $request->getHeaderLine('X-Condominium-ID');
            if (empty($headerTenant)) {
                $headerTenant = $request->getHeaderLine('X-Condo-Id');
            }
            if (!empty($headerTenant)) {
                $tenantId = (int) $headerTenant;
            }
        }

        // 2. Compatibilidad Web Panel: Buscar el tenant dictado por la sesión PHP 
        if (!$tenantId) {
            $session = session();
            if ($session->has('current_condominium_id')) {
                $tenantId = (int) $session->get('current_condominium_id');
            } else if ($session->has('condominium_id')) {
                $tenantId = (int) $session->get('condominium_id');
            } else {
                // Fallback: Si recién inicia sesión y no tiene condo activo, buscar el primero asignado
                $userId = $session->get('user_id') ?? ($session->get('user')['id'] ?? null);
                if ($userId) {
                    $pivot = \Config\Database::connect()
                        ->table('user_condominium_roles')
                        ->where('user_id', $userId)
                        ->get()
                        ->getRow();
                        
                    if ($pivot) {
                        $tenantId = (int) $pivot->condominium_id;
                        $session->set('condominium_id', $tenantId);
                    }
                }
            }
        }

        // Si no tenemos manera de saber el condominio, rechazamos la petición
        if ($tenantId === null || $tenantId === '') {
            // Respuesta JSON para PWA/API
            $isAjax = $request->hasHeader('X-Requested-With') && strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
            if ($isAjax || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                return response()->setJSON(['error' => 'Acceso denegado: Tenant no especificado o inválido.'])->setStatusCode(401);
            }
            // Respuesta Web para navegadores
            return redirect()->to('/login')->with('error', 'Por favor, selecciona un condominio para acceder a este módulo.');
        }

        // ── SuperAdmin global (tenantId=0) no puede acceder a rutas /admin/ ──
        // Esas rutas requieren un condominio real. Redirigir al panel SuperAdmin.
        if ($tenantId === 0) {
            $isAjax = $request->hasHeader('X-Requested-With') && strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
            if ($isAjax || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                return response()->setJSON([
                    'success' => false,
                    'message' => 'Acceso denegado: Debes seleccionar un condominio para acceder a este módulo.'
                ])->setStatusCode(403);
            }
            return redirect()->to('/superadmin/dashboard')
                ->with('warning', 'Para acceder a la configuración de un condominio, primero selecciona uno.');
        }

        // ── Verificar si el condominio está suspendido o eliminado ──
        $db = \Config\Database::connect();
        $condoData = $db->table('condominiums')
            ->select('status, subscription_status, plan_expires_at, stripe_subscription_id, deleted_at')
            ->where('id', $tenantId)
            ->get()
            ->getRow();

        if ($condoData) {
            $isAjax = $request->hasHeader('X-Requested-With') && strtolower($request->getHeaderLine('X-Requested-With')) === 'xmlhttprequest';
            $wantsJson = $isAjax || strpos($request->getHeaderLine('Accept'), 'application/json') !== false;

            if ($condoData->deleted_at !== null) {
                // Condominio Eliminado Lógicamente
                if ($wantsJson) {
                    return response()->setJSON([
                        'success' => false,
                        'message' => 'Esta comunidad ya no existe o fue eliminada.',
                        'code'    => 'COMMUNITY_DELETED'
                    ])->setStatusCode(403);
                }
                session()->remove('condominium_id');
                session()->remove('current_condominium_id');
                return redirect()->to('/auth/select-tenant')
                    ->with('error', '🚫 La comunidad a la que intentas acceder fue eliminada.');
            }

            $isSuspended = ($condoData->status === 'suspended' || in_array($condoData->subscription_status, ['suspended', 'canceled']));
            
            // Check for trial expiration
            $stripeSubId = $condoData->stripe_subscription_id;
            $expiresAt = $condoData->plan_expires_at;
            $isTrialExpired = (!$stripeSubId && $expiresAt && strtotime($expiresAt) < time());

            if ($isSuspended || $isTrialExpired) {
                $allowedPaths = [
                    'admin/dashboard',
                    'admin/configuracion',
                    'admin/switch-condo',
                    'admin/onboarding',
                    'api/v1/auth',
                    'auth',
                    'logout'
                ];
                
                $currentPath = uri_string();
                $isAllowed = false;
                foreach ($allowedPaths as $path) {
                    if (strpos($currentPath, $path) === 0) {
                        $isAllowed = true;
                        break;
                    }
                }

                if (!$isAllowed) {
                    if ($wantsJson) {
                        return response()->setJSON([
                            'success' => false,
                            'message' => 'Tu suscripción está suspendida, cancelada o ha expirado. Actualiza tu método de pago.',
                            'code'    => 'SUBSCRIPTION_SUSPENDED'
                        ])->setStatusCode(403);
                    }
                    return redirect()->to('/admin/dashboard');
                }
            }
        }

        // Inyectamos el tenant al Kernel (Servicio Singleton)
        TenantService::getInstance()->setTenantId($tenantId);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se requiere procesamiento a la salida
    }
}
