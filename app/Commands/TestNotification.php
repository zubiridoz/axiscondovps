<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestNotification extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:notification';
    protected $description = 'Envía una notificación PUSH real a un usuario para probar.';
    
    protected $usage       = 'test:notification [user_id]';
    protected $arguments   = [
        'user_id' => 'ID del usuario al que se le enviará (opcional, por defecto el usuario 11)',
    ];

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $userId = $params[0] ?? 11;
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        
        // Find condo
        $pivot = $db->table('user_condominium_roles')->where('user_id', $user['id'])->get()->getRowArray();
        if ($pivot) {
            $condo = $db->table('condominiums')->where('id', $pivot['condominium_id'])->get()->getRowArray();
        } else {
            $condo = $db->table('condominiums')->where('id', 2)->get()->getRowArray();
        }

        if (!$user || !$condo) {
            CLI::error("No se encontró usuario o condominio para hacer la prueba.");
            return;
        }

        CLI::write("Preparando envío a Usuario ID: {$user['id']} ({$user['first_name']} {$user['last_name']}) en Condominio ID: {$condo['id']}", 'cyan');

        try {
            // Inyectar contexto para que BaseTenantModel lo apruebe
            \App\Services\TenantService::getInstance()->setTenantId($condo['id']);
            
            // true para que dispare el push FCM a sus dispositivos
            $id = \App\Models\Tenant\NotificationModel::notify(
                $condo['id'],
                $user['id'],
                'payment_reminder',
                '🔔 Prueba de Sistema',
                'Hola ' . $user['first_name'] . ', esto es una prueba PUSH desde la consola (Arquitectura Híbrida).',
                ['type' => 'payment_reminder', 'test' => true],
                true 
            );
            
            CLI::write("✅ Notificación y Push enviados correctamente. ID de tabla `notifications`: {$id}", 'green');
            
            // Check if user has tokens
            $tokens = $db->table('device_push_subscriptions')->where('user_id', $user['id'])->get()->getResultArray();
            if (empty($tokens)) {
                CLI::write("⚠️ ADVERTENCIA: El usuario ID {$user['id']} NO tiene ningún token registrado en 'device_push_subscriptions'. NO recibirá Push en su celular.", 'red');
            } else {
                CLI::write("ℹ️ El usuario tiene " . count($tokens) . " dispositivo(s) vinculado(s).", 'cyan');
            }
            
        } catch (\Exception $e) {
            CLI::error("Error durante el envío: " . $e->getMessage());
        }
    }
}
