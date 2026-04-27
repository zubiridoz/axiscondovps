<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;
use App\Services\TenantService;

/**
 * BaseTenantModel
 * 
 * Todos los modelos que pertenecen a un condominio específico (units, residents, etc.)
 * deben extender esta clase para inyectar automáticamente el aislamiento lógico (WHERE condominium_id).
 */
abstract class BaseTenantModel extends Model
{
    // Registramos los callbacks de CodeIgniter 4
    protected $beforeFind     = ['applyTenantScope'];
    protected $beforeInsert   = ['injectTenantId'];
    protected $beforeUpdate   = ['applyTenantScope'];
    protected $beforeDelete   = ['applyTenantScope'];

    /**
     * Valida y aplica de forma estricta el tenant activo.
     */
    protected function enforceTenantScope()
    {
        $tenantService = TenantService::getInstance();
        
        if ($tenantService->hasTenant()) {
            // Temporal debug log requestado
            log_message('debug', 'Tenant actual: ' . $tenantService->getTenantId());
            
            // Si el tenantId es 0, significa que estamos operando como SUPER ADMIN GLOBAL.
            // Permitimos el paso sin aplicar ningún filtro 'WHERE condominium_id'.
            if ($tenantService->getTenantId() !== 0) {
                // Se asegura de que la tabla actual filtre por su condominium_id
                $this->where($this->table . '.condominium_id', $tenantService->getTenantId());
            }
        } else {
            // Fallback seguro: sin tenant no hay resultados cruzados
            if (ENVIRONMENT === 'development' || ENVIRONMENT === 'testing') {
                throw new \RuntimeException("[SECURITY] Aislamiento roto: Se intentó consultar {$this->table} sin un TenantId activo.");
            } else {
                log_message('critical', "[SECURITY] Query bloqueada en {$this->table}: falta TenantId activo.");
                $this->where('1 = 0'); // Bloquea la obtención de resultados cruzados
            }
        }
    }

    /**
     * Sobreescribimos countAllResults porque CodeIgniter 4 bypasses 'beforeFind' para conteos.
     */
    public function countAllResults(bool $reset = true, bool $test = false)
    {
        $this->enforceTenantScope();
        return parent::countAllResults($reset, $test);
    }

    /**
     * Sobreescribimos countAll para el mismo fin.
     */
    public function countAll(bool $reset = true, bool $test = false)
    {
        $this->enforceTenantScope();
        return parent::countAll($reset, $test);
    }

    /**
     * Aplica el contexto del inquilino (WHERE condominium_id) antes de
     * ejecutar consultas SELECT, UPDATE o DELETE.
     */
    protected function applyTenantScope(array $data)
    {
        $this->enforceTenantScope();
        
        return $data;
    }

    /**
     * Inyecta el ID del condominio automáticamente antes de hacer un INSERT.
     */
    protected function injectTenantId(array $data)
    {
        $tenantService = TenantService::getInstance();
        
        if ($tenantService->hasTenant()) {
            // Aseguramos que data exista
            if (isset($data['data'])) {
                // Si la tabla no manda el id, lo forzamos con el contexto de seguridad
                if (!isset($data['data']['condominium_id']) && $tenantService->getTenantId() !== 0) {
                    $data['data']['condominium_id'] = $tenantService->getTenantId();
                }
            }
        } else {
            if (ENVIRONMENT === 'development' || ENVIRONMENT === 'testing') {
                throw new \RuntimeException("[SECURITY] Aislamiento roto: Intento de INSERT en {$this->table} sin TenantId.");
            } else {
                log_message('critical', "[SECURITY] Insert denegado en {$this->table}: Falta TenantId.");
                throw new \RuntimeException("Acción denegada: Falta contexto de seguridad en la creación.");
            }
        }
        
        return $data;
    }
}
