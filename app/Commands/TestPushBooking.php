<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestPushBooking extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:push-booking';
    protected $description = 'Prueba el envío de notificación push al aprobar/rechazar reserva';

    public function run(array $params)
    {
        CLI::write("Testing Push Notification for Booking...", 'cyan');

        try {
            $pushService = new \App\Services\Notifications\PushNotificationService();
            
            // Try sending a message to a fake token
            $fakeToken = 'fake-token-12345';
            
            CLI::write("Attempting to send push to fake token...", 'yellow');
            
            $success = $pushService->sendToTokens([$fakeToken], 'Reserva Aprobada', 'Prueba', ['type' => 'amenity', 'booking_id' => '123']);
            
            // It will return false because the token is fake, but we can read the logs
            if ($success) {
                CLI::write("✓ Send returned true", 'green');
            } else {
                CLI::write("✗ Send returned false (expected with fake token)", 'yellow');
            }
            
            CLI::write("Verify complete. Check CI logs for details.", 'cyan');
        } catch (\Throwable $e) {
            CLI::error("Error: " . $e->getMessage());
        }
    }
}
