<?php

namespace App\Services;

use CodeIgniter\Database\BaseBuilder;

/**
 * AuditService
 * 
 * Rastrea silenciosamente los movimientos críticos del sistema en una supuesta 
 * tabla multi-tenant inmutable de `audit_logs`.
 */
class AuditService
{
    /**
     * Grabar una acción auditable en Base de Datos.
     * 
     * @param int $userId Identificador del Usuario Operador
     * @param string $action Verbo o evento (ej: 'LOGIN_SUCCESS', 'PLAN_CHANGED', 'TENANT_SUSPENDED')
     * @param string $entity Módulo afectado (ej: 'Condominium', 'Subscription', 'Ticket')
     * @param int|null $entityId ID del registro afectado 
     */
    public function logAction(int $userId, string $action, string $entity, ?int $entityId = null)
    {
        $db = \Config\Database::connect();
        
        $logData = [
            'user_id'    => $userId,
            'action'     => $action,
            'entity'     => $entity,
            'entity_id'  => $entityId,
            'ip_address' => \Config\Services::request()->getIPAddress(),
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Al no crear la migración explícita hoy para cumplir la restricción,
        // lo intentamos grabar. Si la tabla no existe en DB vieja, hacemos fallback a un Log local de CI4 para evitar rompimiento HTTP 500.
        if ($db->tableExists('audit_logs')) {
            $db->table('audit_logs')->insert($logData);
        } else {
            // Proof-of-concept Fallback
            $msg = sprintf(
                "[AUDIT LOG] Acción: %s | Modulo: %s | RegistroID: %s | OperadorID: %s | IP: %s",
                $action, $entity, $entityId ?? '-', $userId, $logData['ip_address']
            );
            log_message('critical', $msg);
        }
    }
}
