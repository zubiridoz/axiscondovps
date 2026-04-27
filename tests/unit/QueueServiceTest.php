<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\QueueService;

/**
 * QueueServiceTest
 * 
 * Verificación del motor simulado de Job Queues (Fase 15).
 */
class QueueServiceTest extends CIUnitTestCase
{
    public function testJobDispatchingReturnsTrue()
    {
        $queueSvc = new QueueService();
        
        // Simular despacho de un Job genérico
        $dispatched = $queueSvc->dispatch(
            'App\Jobs\SendPushNotificationJob',
            ['user_id' => 5, 'message' => 'Test'],
            'high'
        );

        // La inserción en el Broker / BD debe ser exitosa
        $this->assertTrue($dispatched);
    }

    public function testJobRetryingOutputsCorrectSignal()
    {
        $queueSvc = new QueueService();
        $retried = $queueSvc->retry('JOB-UUID-999');
        $this->assertTrue($retried);
    }

    public function testJobMarkedAsFailedStopsProcessing()
    {
        $queueSvc = new QueueService();
        $failed = $queueSvc->fail('JOB-UUID-001', new \RuntimeException("Falla de CPU simulada"));
        
        // Verifica que la librería logre cambiar su status letal
        $this->assertTrue($failed);
    }
}
