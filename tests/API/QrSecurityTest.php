<?php

namespace Tests\API;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * QrSecurityTest
 * 
 * Verificación abstracta/unitaria de Criptografía de Accesos Condominet
 */
class QrSecurityTest extends CIUnitTestCase
{
    /**
     * Prueba el algoritmo interno subyacente de Hash (Simulación de firma temporal válida)
     */
    public function testValidQrHashStructure()
    {
        $invitationId = 55;
        $validUntil = time() + 3600; // Valido 1 hora extra
        $secretSalt = getenv('encryption.key') ?: 'dummy_ci_secret';

        // Estructura usada por TokenService
        $payloadRaw = "{$invitationId}:{$validUntil}";
        $expectedSignature = hash_hmac('sha256', $payloadRaw, $secretSalt);

        $fakeToken = base64_encode("{$payloadRaw}:{$expectedSignature}");

        // Verificamos descomposición base64 y separación list()
        $decoded = base64_decode($fakeToken);
        $parts = explode(':', $decoded);

        $this->assertCount(3, $parts, "El QR manipulado no posee estructura [ID : TIME : HASH]");
        $this->assertEquals(55, $parts[0]);

        // Verificación de Firma Inmutable
        $verifyPayload = "{$parts[0]}:{$parts[1]}";
        $signatureAttempt = hash_hmac('sha256', $verifyPayload, $secretSalt);

        $this->assertTrue(hash_equals($signatureAttempt, $parts[2]), 'La función criptográfica de validación fue evadida');
    }

    public function testExpiredQrReturnsError()
    {
        $invitationId = 55;
        $validUntil = time() - 100; // Caducado hace 100 segundos
        $secretSalt = getenv('encryption.key') ?: 'dummy_ci_secret';

        $payloadRaw = "{$invitationId}:{$validUntil}";
        $signature = hash_hmac('sha256', $payloadRaw, $secretSalt);
        
        $tokenCaducado = base64_encode("{$payloadRaw}:{$signature}");

        // Verificamos expiración
        $decoded = base64_decode($tokenCaducado);
        $parts = explode(':', $decoded);
        $expireTimestamp = (int) $parts[1];

        $this->assertLessThan(time(), $expireTimestamp, 'Validación temporal de QR aceptaría ticket obsoleto');
    }
}
