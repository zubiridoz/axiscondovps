<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Rate Limit Configuration
 * Centraliza los límites de velocidad para la API móvil de AxisCondo.
 */
class RateLimit extends BaseConfig
{
    /**
     * Activa o desactiva el throttling globalmente.
     */
    public bool $enabled = true;

    /**
     * FASE 1: Observación
     * Cuando es TRUE, el sistema NO bloquea ninguna petición (no devuelve 429).
     * Solo registra en los logs cuando se exceden los límites.
     */
    public bool $observationMode = true;

    /**
     * Límites configurables por categoría:
     * 'clave' => [Max Peticiones, Ventana de Tiempo en Segundos]
     */
    public array $limits = [
        'login'    => [30, 60],   // 30 por minuto (basado en IP + Email)
        'uploads'  => [10, 60],   // 10 por minuto (basado en UserID)
        'password' => [5,  60],   // 5 por minuto (basado en UserID)
        'qr'       => [180, 60],  // 180 por minuto (basado en UserID del guardia)
    ];
}
