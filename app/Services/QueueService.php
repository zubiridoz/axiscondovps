<?php

namespace App\Services;

/**
 * QueueService
 * 
 * Gestiona tareas en segundo plano (Job Queue) para evitar bloquear el request PWA principal.
 * Al no poder modificar la BD para crear tabla `jobs`, este servicio actúa como
 * un envoltorio preparado (que de fondo puede usar Redis Pub/Sub o la tabla cuando se genere).
 */
class QueueService
{
    /**
     * Encola una tarea
     * 
     * @param string $jobClass El namespace del Worker/Job a ejecutar
     * @param array $payload Los datos necesarios para el Job (ej. fcm_token, invoice_id)
     * @param string $queue El nombre de la línea/tubo en el broker (ej 'high', 'emails')
     */
    public function dispatch(string $jobClass, array $payload, string $queue = 'default')
    {
        // POC: En un stack CI4 de alta concurrencia, esto inserta en DB `jobs` o en Redis
        // Como restricción arquitectónica, simularemos la escritura al handler predefinido por el motor
        log_message('info', "[QUEUE] Job {$jobClass} despachado a la cola '{$queue}'. Payload: " . json_encode($payload));

        return true; 
    }

    /**
     * Comando para el Worker Demonizado (cli)
     * Procesa los Jobs atrapados en la cola
     */
    public function processQueue(string $queue = 'default')
    {
        // Loop principal del worker:
        // 1. Obtiene el Job de la BD / Redis
        // 2. Ejecuta $jobClass->handle($payload)
        // 3. Marca como processed o failed
        log_message('info', "[WORKER] Escuchando cola {$queue}...");
    }

    /**
     * Reencolar Tarea Fallida (Retry Mechanism)
     */
    public function retry(string $jobId)
    {
         log_message('info', "[QUEUE] Reencolando Job Fallido ID: {$jobId}.");
         return true;
    }

    /**
     * Marca explícitamente un Job como fallido letal, deteniendo sus retries.
     */
    public function fail(string $jobId, \Exception $e)
    {
        log_message('critical', "[QUEUE] Job ID: {$jobId} ha fallado definitivamente. Error: " . $e->getMessage());
        return true;
    }
}
