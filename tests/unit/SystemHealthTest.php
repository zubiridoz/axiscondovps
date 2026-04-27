<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * SystemHealthTest
 * 
 * Verifica que el Monitor de Kubernetes/Load Balancer pueda leer `UP`.
 */
class SystemHealthTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLivenessEndpoint()
    {
        $result = $this->call('get', '/api/v1/health');
        
        $result->assertStatus(200);
        $result->assertJSONFragment(['status' => 'UP']);
    }

    public function testMetricsEndpointFormat()
    {
        // El de metrics verifica componentes profundos. 
        // Si no hay redis configurado real, puede dar DEGRADED (503), pero el JSON debe respetar la anatomía dictada.
        $result = $this->call('get', '/api/v1/health/metrics');
        
        // Assert JSON estructura
        $json = json_decode($result->getJSON(), true);

        $this->assertArrayHasKey('status', $json);
        $this->assertArrayHasKey('components', $json);
        $this->assertArrayHasKey('database', $json['components']);
        $this->assertArrayHasKey('cache', $json['components']);
        
        // La base de datos SIEMPRE debe estar en UP, incluso en tests si CI4 está vivo
        $this->assertEquals('UP', $json['components']['database'], 'La DB falló el Ping durante la métrica de Salud');
    }
}
