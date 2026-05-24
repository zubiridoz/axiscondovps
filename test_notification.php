<?php
// Validar acceso por CLI o definir entorno
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . 'system/bootstrap.php';

$condoId = 1; // Assuming condo ID 1 exists
$userId = 1;  // Assuming user ID 1 exists

// Let's find a valid condo and user from DB
$db = \Config\Database::connect();
$condo = $db->table('condominiums')->get()->getRowArray();
$user = $db->table('users')->get()->getRowArray();

if ($condo && $user) {
    try {
        $id = \App\Models\Tenant\NotificationModel::notify(
            $condo['id'],
            $user['id'],
            'payment_reminder',
            'Test Reminder',
            'This is a test payment reminder for user ' . $user['id'],
            ['type' => 'payment_reminder', 'test' => true],
            false // sendPush = false to avoid FCM hanging
        );
        echo "Notification created successfully with ID: " . $id . "\n";
        
        $notif = $db->table('notifications')->where('id', $id)->get()->getRowArray();
        echo "DB Entry:\n";
        print_r($notif);
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No valid condo or user found to test with.\n";
}
