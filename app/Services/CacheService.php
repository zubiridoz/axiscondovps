<?php

namespace App\Services;

/**
 * CacheService
 * 
 * Capa de abstracción sobre el motor de caché nativo de CodeIgniter 4 (Redis/Memcached/File).
 * Centraliza la caché para planes SaaS, RBAC y Dashboard Configurations.
 */
class CacheService
{
    protected $cache;

    public function __construct()
    {
        // Usa la configuración definida en app/Config/Cache.php (Idealmente Redis en Producción)
        $this->cache = \Config\Services::cache();
    }

    /**
     * Obtener valor del Cache
     */
    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    /**
     * Guardar valor en Cache
     * @param string $key La llave identificadora
     * @param mixed $value El contenido a guardar (arrays/objetos deben ser serializables)
     * @param int $ttl Segundos que vivirá el dato
     */
    public function set(string $key, $value, int $ttl = 3600)
    {
        return $this->cache->save($key, $value, $ttl);
    }

    /**
     * Patrón Remember: Devuelve el valor si existe, si no, ejecuta el callback, lo guarda y lo devuelve.
     * Muy útil para los filtros RBAC y Dashboard PWA.
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        $value = $this->get($key);

        if ($value !== null) {
            return $value; // Hit
        }

        // Miss - regenerar Data
        $value = $callback();
        $this->set($key, $value, $ttl);

        return $value;
    }

    /**
     * Limpia llave específica
     */
    public function delete(string $key)
        {
        return $this->cache->delete($key);
    }
}
