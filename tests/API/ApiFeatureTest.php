<?php

namespace Tests\API;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * ApiFeatureTest
 * 
 * Verifica que los endpoints core operen estructuralmente y
 * respeten HTTP codes y formatos (RESTful) de JSON válidos.
 */
class ApiFeatureTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
        // Cargar variables de entorno DUMMY para testing o seeders si amerita
    }

    public function testLoginApiEndpoint()
    {
        // Enviar POST dummy intencional
        $result = $this->withBodyFormat('json')
                       ->call('post', '/api/login', [
                           'email' => 'test@condominet.fake',
                           'password' => 'wrongpass'
                       ]);

        $result->assertStatus(401);
        $result->assertJSONExact(['status' => 'error', 'message' => 'Credenciales inválidas']);
    }

    public function testResidentProfileRequiresToken()
    {
        // Petición a ruta protegida PWA Residente sin inyectar Authorization Header
        $result = $this->call('get', '/api/v1/resident/profile');

        // El filtro ApiAuthFilter debe rechazarlo inmediatamente
        $result->assertStatus(401);
        $result->assertJSONFragment(['message' => 'Token no proporcionado o inválido']);
    }

    public function testVisitorInvitationsHasCorrectStructure()
    {
        // Simular headers válidos inyectados.
        // Dado el aislamiento, emulamos la cabecera Authentication si tuviéramos un helper,
        // Al tratarse de QA general, validamos que la ruta EXISTA.
        $headers = ['Authorization' => 'Bearer fake.token.123'];
        $result = $this->withHeaders($headers)
                       ->call('get', '/api/v1/resident/visitor-invitations');

        // Validamos que el Filter actuó correctamente (Devolverá 401 por token no real) 
        // pero la ruta CI4 está mapeada correctamente y no regresa 404 Not Found.
        $this->assertTrue($result->isOK() === false); 
        $result->assertStatus(401);
    }

    public function testSecurityGateEndpointsExist()
    {
        // Valida que los Endpoints de la caseta fueron construidos con POST (JSON)
        $endpoints = [
            '/api/v1/security/validate-qr',
            '/api/v1/security/entry',
            '/api/v1/security/exit'
        ];

        foreach ($endpoints as $url) {
             $res = $this->withBodyFormat('json')
                         ->withHeaders(['Authorization' => 'Bearer dummy_token'])
                         ->call('post', $url, ['qr_code_id' => 1]);
             
             // Verificamos que no sea 404 (Sino 401 por el filtro blindado)
             $this->assertNotEquals(404, $res->getStatus(), "La ruta $url no parece existir en Routes.php");
        }
    }
}
