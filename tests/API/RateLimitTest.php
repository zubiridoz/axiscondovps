<?php

namespace Tests\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * RateLimitTest
 * 
 * Valida la protección impuesta en Hardening (`RateLimitFilter`) contra fuerza bruta.
 */
class RateLimitTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testBruteForceLoginBlock()
    {
        // El RateLimitFilter para Login es <= 5 / minuto.
        // Haremos 6 peticiones forzadas al mismo endpoint desde el framework FeatureTest.

        for ($i = 1; $i <= 5; $i++) {
            $result = $this->withBodyFormat('json')
                           ->call('post', '/api/login', [
                               'email'    => 'hack@attempt.com',
                               'password' => 'wrong'
                           ]);

            // Deben retornar HTTP 401 normal porque el intento de login falla la clave
            // (pero el firewall aún permite cruzar)
            $this->assertEquals(401, $result->getStatus());
        }

        // --- Intento #6: Debería accionar Bloqueo por Rate Limiter ---
        $result = $this->withBodyFormat('json')
                       ->call('post', '/api/login', [
                           'email'    => 'hack@attempt.com',
                           'password' => 'wrong'
                       ]);

        // Verificamos protección Anti-DDoS del Throttler
        $result->assertStatus(429); // Too Many Requests
    }
}
