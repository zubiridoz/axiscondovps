<?php

namespace App\Services;

use App\Models\Tenant\CondominiumModel;
use App\Services\CacheService;

/**
 * CondominiumSettingsService
 * 
 * Centraliza la lectura de la configuración en formato JSON (`settings_json`) 
 * de cada condominio, permitiendo encender/apagar módulos de la PWA de forma dinámica.
 * Utiliza Redis/CacheLayer para evitar golpear la tabla root constantemente.
 */
class CondominiumSettingsService
{
    protected int $condominiumId;

    public function __construct(int $condominiumId)
    {
        $this->condominiumId = $condominiumId;
    }

    /**
     * Devuelve TODA la configuración del condominio decodificada, apoyada de caché.
     */
    public function getAllSettings(): array
    {
        $cache = new CacheService();
        $cacheKey = "tenant_settings_json_" . $this->condominiumId;

        return $cache->remember($cacheKey, 3600, function() {
            return $this->buildFromDatabase();
        });
    }

    /**
     * Consulta base de datos primaria
     */
    private function buildFromDatabase(): array
    {
        $model = new CondominiumModel();
        // Usando Model Method para saltarse TenantFilter si es necesario en root SaaS, o usando la instancia que ya filtra
        $condominium = $model->find($this->condominiumId);

        if (!$condominium) return [];

        // Por lo general, 'settings_json' es una columna MEDIUMTEXT en la DB SaaS
        // Ejemplo de Data: {"features": {"amenities": true, "parcels": false, "polls": true}, "theme_color": "#198754"}
        
        if (isset($condominium['settings_json']) && !empty($condominium['settings_json'])) {
            return json_decode($condominium['settings_json'], true) ?? [];
        }

        // Default Config Base
        return [
            'features' => [
                 'amenities'     => true,
                 'parcels'       => true,
                 'polls'         => true,
                 'tickets'       => true,
                 'visitor_qr'    => true,
                 'announcements' => true
            ],
            'theme_color' => '#0d6efd'
        ];
    }

    /**
     * Verifica si una funcionalidad "Feature Toggle" específica está habilitada en este condominio
     */
    public function isFeatureEnabled(string $featureKey): bool
    {
        $settings = $this->getAllSettings();
        
        if (isset($settings['features']) && isset($settings['features'][$featureKey])) {
            return (bool) $settings['features'][$featureKey];
        }

        // Denegar por defecto si no se conoce
        return false;
    }

    /**
     * Forza el vaciado de caja al actualizar el Administrador sus Configs
     */
    public function flushCache()
    {
        $cache = new CacheService();
        $cacheKey = "tenant_settings_json_" . $this->condominiumId;
        $cache->delete($cacheKey);
    }
}
