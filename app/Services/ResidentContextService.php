<?php

namespace App\Services;

use App\Models\Tenant\ResidentModel;

/**
 * ResidentContextService
 * 
 * Fuente única de verdad para el contexto del residente en cada request.
 * Resuelve: resident_id, unit_id, condominium_id, resident_type.
 * 
 * Flujo:
 *  1. ApiAuthFilter llama resolve() después de validar tenant
 *  2. Si X-Unit-Id viene → valida pertenencia, 403 si inválido (SIN fallback)
 *  3. Si X-Unit-Id no viene → fallback a primer registro (backward compatible)
 *  4. Controllers usan getters en vez de ->where('user_id', $userId)->first()
 * 
 * Stateless per-request. No persiste estado entre requests.
 */
class ResidentContextService
{
    private static ?self $instance = null;

    private ?int $residentId = null;
    private ?int $unitId = null;
    private ?int $condominiumId = null;
    private ?string $residentType = null; // 'owner' | 'tenant'
    private ?array $residentRecord = null;
    private bool $resolved = false;

    /**
     * Singleton para mantener el contexto consistente durante todo el request.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Resuelve el contexto del residente.
     * 
     * @param int $userId           ID del usuario autenticado
     * @param int $condominiumId    ID del condominio activo (ya validado por TenantService)
     * @param int|null $requestedUnitId  Unit ID del header X-Unit-Id (opcional)
     * 
     * @return bool  true si se resolvió correctamente, false si X-Unit-Id es inválido
     */
    public function resolve(int $userId, int $condominiumId, ?int $requestedUnitId = null): bool
    {
        $this->condominiumId = $condominiumId;

        $residentModel = new ResidentModel();

        if ($requestedUnitId !== null && $requestedUnitId > 0) {
            // ═══ Caso A: X-Unit-Id explícito → validar pertenencia estricta ═══
            $resident = $residentModel
                ->where('user_id', $userId)
                ->where('unit_id', $requestedUnitId)
                ->where('condominium_id', $condominiumId)
                ->first();

            if (!$resident) {
                // X-Unit-Id no pertenece a este usuario en este condominio → RECHAZAR
                log_message('warning', "[RESIDENT_CTX] X-Unit-Id={$requestedUnitId} no pertenece a user={$userId} en condo={$condominiumId}");
                return false;
            }

            $this->fillFromRecord($resident);
            return true;
        }

        // ═══ Caso B: Sin X-Unit-Id → fallback a primer registro (backward compatible) ═══
        $resident = $residentModel
            ->where('user_id', $userId)
            ->where('condominium_id', $condominiumId)
            ->orderBy('id', 'ASC')
            ->first();

        if ($resident) {
            $this->fillFromRecord($resident);
        }

        // Aún si no hay resident record (admin puro), marcamos como resuelto
        $this->resolved = true;
        return true;
    }

    /**
     * Llena las propiedades internas desde un registro de la tabla residents.
     */
    private function fillFromRecord(array $resident): void
    {
        $this->residentId = (int) $resident['id'];
        $this->unitId = !empty($resident['unit_id']) ? (int) $resident['unit_id'] : null;
        $this->residentType = $resident['type'] ?? 'tenant';
        $this->residentRecord = $resident;
        $this->resolved = true;
    }

    // ─────────────────────────────────────────────────
    // GETTERS PÚBLICOS
    // ─────────────────────────────────────────────────

    public function getResidentId(): ?int
    {
        return $this->residentId;
    }

    public function getUnitId(): ?int
    {
        return $this->unitId;
    }

    public function getCondominiumId(): ?int
    {
        return $this->condominiumId;
    }

    /**
     * Retorna 'owner' o 'tenant' según el tipo del registro
     * activo del residente en la unidad seleccionada.
     */
    public function getResidentType(): ?string
    {
        return $this->residentType;
    }

    /**
     * Retorna el registro completo de la tabla `residents` para la
     * unidad activa. Equivalente al antiguo ->first().
     */
    public function getResidentRecord(): ?array
    {
        return $this->residentRecord;
    }

    /**
     * Indica si el contexto ya fue resuelto en este request.
     * Útil para Services que pueden ejecutarse tanto en HTTP como en CLI/cron.
     */
    public function isResolved(): bool
    {
        return $this->resolved;
    }

    /**
     * Reset para testing o re-resolución (no debería usarse en producción).
     */
    public function reset(): void
    {
        $this->residentId = null;
        $this->unitId = null;
        $this->condominiumId = null;
        $this->residentType = null;
        $this->residentRecord = null;
        $this->resolved = false;
    }
}
