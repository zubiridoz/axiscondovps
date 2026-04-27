<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Auth\TokenService;

/**
 * ApiAuthFilter (Production-Ready)
 * 
 * Protege rutas API exigiendo Bearer Token + X-Condo-Id.
 * 
 * Flujo:
 *  1. Valida Bearer Token → 401 si falla
 *  2. Lee X-Condo-Id header
 *     - API (/api/*): REQUERIDO → 400 si falta
 *     - Web (/admin/*): Opcional → fallback al primer pivot
 *  3. Valida pertenencia user ∈ condominio → 403 si falla
 *  4. Setea TenantService para que BaseTenantModel funcione
 * 
 * Stateless: cada request se resuelve independientemente.
 * Cache: validación en memoria para evitar queries duplicados en el mismo request.
 */
class ApiAuthFilter implements FilterInterface
{
    /**
     * Cache en memoria por request para evitar queries duplicados.
     * Key: "{userId}_{condoId}" → true/false (pertenencia validada)
     */
    private static array $validatedPivots = [];

    public function before(RequestInterface $request, $arguments = null)
    {
        // ═══════════════════════════════════════════════
        // PASO 1: Validar Bearer Token
        // ═══════════════════════════════════════════════
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
            $tokenQuery = $request->getGet('token');
            if ($tokenQuery) {
                $authHeader = 'Bearer ' . $tokenQuery;
            } else {
                return response()->setJSON(['error' => 'No Autorizado: Falta Header Bearer Token'])->setStatusCode(401);
            }
        }
        
        $plainToken = substr($authHeader, 7);
        $tokenService = new TokenService();
        $tokenData = $tokenService->validateToken($plainToken);
        
        if (!$tokenData) {
            return response()->setJSON(['error' => 'No Autorizado: Token inválido o expirado'])->setStatusCode(401);
        }
        
        // PHP 8.2+: Header seguro + propiedad dinámica para compatibilidad
        $request->setHeader('X-Auth-UserId', (string) $tokenData['user_id']);
        @$request->userId = $tokenData['user_id'];
        
        $userId = (int) $tokenData['user_id'];

        // ═══════════════════════════════════════════════
        // PASO 2: Resolver Tenant (stateless, per-request)
        // ═══════════════════════════════════════════════
        $rawCondoHeader = $request->getHeaderLine('X-Condo-Id');
        $isApiRoute     = $this->isApiRoute($request);

        // ── Caso A: Header presente → validar y usar ──
        if (!empty($rawCondoHeader)) {
            // Hardening: validación estricta de input
            if (!is_numeric($rawCondoHeader) || (int) $rawCondoHeader <= 0) {
                log_message('warning', "[TENANT] Header X-Condo-Id inválido: '{$rawCondoHeader}' | user={$userId}");
                file_put_contents(WRITEPATH . 'logs/debug_tenant.txt', date('Y-m-d H:i:s') . " - [TENANT] Header X-Condo-Id inválido: '{$rawCondoHeader}' | user={$userId}\n", FILE_APPEND);
                return $this->rejectBadRequest('X-Condo-Id debe ser un entero positivo, recibido: ' . $rawCondoHeader);
            }

            $condoId = (int) $rawCondoHeader;
            return $this->resolveAndSetTenant($request, $userId, $condoId);
        }

        // ── Caso B: Header ausente ──
        if ($isApiRoute) {
            // Rutas que explícitamente no requieren X-Condo-Id porque apenas van a obtenerlo o no dependen de un condo específico al inicio
            $path = $request->getUri()->getPath();
            if (
                strpos($path, 'api/v1/condominiums/mine') !== false || 
                strpos($path, 'api/v1/condominiums/switch') !== false ||
                strpos($path, 'api/v1/devices/subscribe') !== false ||
                strpos($path, 'api/v1/devices/unsubscribe') !== false
            ) {
                return; // Dejar pasar, no hay tenant id aún
            }

            // API: header REQUERIDO → 400
            log_message('warning', "[TENANT] Falta X-Condo-Id en ruta API | user={$userId} | uri={$path}");
            file_put_contents(WRITEPATH . 'logs/debug_tenant.txt', date('Y-m-d H:i:s') . " - [TENANT] Falta X-Condo-Id en ruta API | user={$userId} | uri={$path}\n", FILE_APPEND);
            return $this->rejectBadRequest('X-Condo-Id header es requerido para: ' . $path);
        }

        // ── Caso C: Ruta web → fallback al primer pivot ──
        return $this->resolveFallbackTenant($request, $userId);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    // ─────────────────────────────────────────────────
    // HELPERS PRIVADOS
    // ─────────────────────────────────────────────────

    /**
     * Valida pertenencia del usuario al condominio y setea TenantService.
     * Usa cache en memoria para evitar queries duplicados.
     */
    private function resolveAndSetTenant(RequestInterface $request, int $userId, int $condoId)
    {
        $cacheKey = "{$userId}_{$condoId}";

        // ── Cache hit: ya validado en este request ──
        if (isset(self::$validatedPivots[$cacheKey])) {
            \App\Services\TenantService::getInstance()->setTenantId($condoId);
            @$request->user_condominio_id = $condoId;
            return;
        }

        // ── Cache miss: consultar DB ──
        $db = \Config\Database::connect();
        $pivot = $db->table('user_condominium_roles')
            ->select('user_condominium_roles.*, roles.name as role_name, condominiums.deleted_at as condo_deleted_at')
            ->join('roles', 'roles.id = user_condominium_roles.role_id', 'left')
            ->join('condominiums', 'condominiums.id = user_condominium_roles.condominium_id', 'inner')
            ->where('user_id', $userId)
            ->where('condominium_id', $condoId)
            ->get()
            ->getRow();

        if (!$pivot || $pivot->condo_deleted_at !== null) {
            log_message('warning', "[SECURITY] Acceso denegado: user={$userId} intentó acceder a condo={$condoId} (inexistente, sin permisos o eliminado)");
            return response()
                ->setJSON([
                    'success' => false,
                    'message' => 'Comunidad no válida o eliminada.',
                    'code'    => 'COMMUNITY_DELETED'
                ])
                ->setStatusCode(403);
        }

        // Hardening: Bloquear usuarios eliminados incompletamente (fantasmas)
        // Residentes típicamente son role_id 4 (o role_name RESIDENT)
        $isResident = (strtoupper($pivot->role_name ?? '') === 'RESIDENT') || ($pivot->role_id == 4);

        file_put_contents(WRITEPATH . 'logs/debug_auth.log', date('Y-m-d H:i:s') . " - USER {$userId} CONDO {$condoId} ROLE_NAME {$pivot->role_name} ROLE_ID {$pivot->role_id} IS_RESIDENT " . ($isResident ? 'Y' : 'N') . "\n", FILE_APPEND);

        if ($isResident) {
            $hasResidentRecord = $db->table('residents')
                ->where('user_id', $userId)
                ->where('condominium_id', $condoId)
                ->countAllResults();

            file_put_contents(WRITEPATH . 'logs/debug_auth.log', date('Y-m-d H:i:s') . " - USER {$userId} HAS_RESIDENT_RECORD {$hasResidentRecord}\n", FILE_APPEND);

            if ($hasResidentRecord === 0) {
                log_message('warning', "[SECURITY] Bloqueo de cuenta fantasma: user={$userId} intentó acceder a condo={$condoId}");
                return response()
                    ->setJSON([
                        'success' => false,
                        'message' => 'Su cuenta en este condominio ha sido desactivada.',
                    ])
                    ->setStatusCode(403);
            }
        }

        // ── Verificar si el condominio está suspendido ──
        $condoRow = $db->table('condominiums')
            ->select('status')
            ->where('id', $condoId)
            ->get()
            ->getRow();

        if ($condoRow && $condoRow->status === 'suspended') {
            log_message('warning', "[SECURITY] Acceso denegado a condominio suspendido: user={$userId} condo={$condoId}");
            return response()
                ->setJSON([
                    'success' => false,
                    'message' => 'Tu comunidad está suspendida. Contacta al administrador del sistema.',
                    'code'    => 'COMMUNITY_SUSPENDED'
                ])
                ->setStatusCode(403);
        }

        // ── Validación exitosa: cachear y setear ──
        self::$validatedPivots[$cacheKey] = true;
        \App\Services\TenantService::getInstance()->setTenantId($condoId);
        @$request->user_condominio_id = $condoId;
    }

    /**
     * Fallback para rutas web: usa el primer condominio del usuario.
     */
    private function resolveFallbackTenant(RequestInterface $request, int $userId)
    {
        $db = \Config\Database::connect();
        $pivot = $db->table('user_condominium_roles')
            ->where('user_id', $userId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getRow();

        if ($pivot && $pivot->condominium_id) {
            \App\Services\TenantService::getInstance()->setTenantId($pivot->condominium_id);
            @$request->user_condominio_id = $pivot->condominium_id;
        }
    }

    /**
     * Determina si el request es a una ruta API (/api/*).
     */
    private function isApiRoute(RequestInterface $request): bool
    {
        $path = $request->getUri()->getPath();
        return strpos($path, '/api/') !== false || strpos($path, 'api/') === 0;
    }

    /**
     * Respuesta 400 estandarizada.
     */
    private function rejectBadRequest(string $message): ResponseInterface
    {
        return response()
            ->setJSON([
                'success' => false,
                'message' => $message,
            ])
            ->setStatusCode(400);
    }
}
