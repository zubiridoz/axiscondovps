<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Database\Exceptions\DatabaseException;

/**
 * SystemHealthController
 * 
 * Monitor de infraestructura. 
 * Expone un Ping general para Kubernetes/LoadBalancers o UptimeRobot.
 * Revisa el estado de la MySQL, y conectividad con la abstracción del Cache.
 */
class SystemHealthController extends ResourceController
{
    /**
     * Endpoint básico (Liveness Probe)
     * GET /health
     */
    public function index()
    {
        return $this->response->setJSON([
            'status' => 'UP',
            'timestamp' => time()
        ]);
    }

    /**
     * Endpoint Avanzado (Readiness / Metrics)
     * GET /metrics
     */
    public function metrics()
    {
        $statusDb = 'DOWN';
        $statusCache = 'DOWN';
        $statusStorage = 'DOWN';

        // 1. Check Database connection
        try {
            $db = \Config\Database::connect();
            $db->query("SELECT 1"); // Dummy Query
            $statusDb = 'UP';
        } catch (\Exception $e) { }

        // 2. Check Cache Layer
        try {
            $cache = \Config\Services::cache();
            if ($cache->getCacheInfo()) {
                $statusCache = 'UP';
            } else {
                // Dummy Save to verify Write
                if ($cache->save('health_test', 1, 10)) $statusCache = 'UP'; 
            }
        } catch (\Exception $e) { }

        // 3. Storage Writability check (Logs folder)
        if (is_writable(WRITEPATH . 'logs')) {
            $statusStorage = 'UP';
        }

        // Global status
        $global = ($statusDb == 'UP' && $statusStorage == 'UP') ? 'OK' : 'DEGRADED';
        $httpCode = ($global == 'OK') ? 200 : 503;

        return $this->response->setJSON([
            'status' => $global,
            'components' => [
                'database' => $statusDb,
                'cache'    => $statusCache,
                'storage'  => $statusStorage
            ],
            'php_version' => PHP_VERSION,
            'environment' => ENVIRONMENT
        ])->setStatusCode($httpCode);
    }
}
