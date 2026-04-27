<?php

namespace App\Services;

/**
 * TenantService
 * 
 * Gestiona el contexto stateful para saber en qué condominio estamos 
 * operando actualmente durante la petición HTTP.
 */
class TenantService
{
    private static ?self $instance = null;
    private ?int $condominiumId = null;

    /**
     * Singleton para asegurar que el tenant sea el mismo en toda la solicitud.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Establece el ID del condominio actual.
     */
    public function setTenantId(int $condominiumId): void
    {
        $this->condominiumId = $condominiumId;
    }

    /**
     * Obtiene el ID del condominio actual.
     */
    public function getTenantId(): ?int
    {
        return $this->condominiumId;
    }

    /**
     * Verifica si hay un condominio activo en el contexto actual.
     */
    public function hasTenant(): bool
    {
        return $this->condominiumId !== null;
    }
}
