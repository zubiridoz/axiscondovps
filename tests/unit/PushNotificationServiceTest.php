<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\Notifications\PushNotificationService;

/**
 * PushNotificationServiceTest
 * 
 * Evaluaciones en seco (sin cURL al internet real) sobre el empaquetado 
 * FCM a los Residentes.
 */
class PushNotificationServiceTest extends CIUnitTestCase
{
    public function testEmptyTokensReturnsFalse()
    {
         $fcm = new PushNotificationService();
         
         // Inyección interna abstracta para no tocar CURL -> forzamos método a nivel test si se abriera (Reflect)
         // Como PHPUnit nativamente rechaza si hay un fallo interno pre-Curl, probamos llamadas con arrays falsos.
         
         // Un role vacío no debe disparar (evitando cuellos de botella Inútiles)
         $res = $fcm->sendToRole('INEXISTENTE_XYZ', 'Titulo', 'Body');
         $this->assertFalse($res, 'Se intentó despachar notificación a un Rol sin usuarios adscritos');
    }
}
