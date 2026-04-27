<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds fcm_token, condominium_id, device_info, and platform columns
 * to device_push_subscriptions table for Flutter FCM support.
 * 
 * Also makes endpoint/p256dh_key/auth_key nullable since Flutter
 * uses FCM tokens instead of Web Push VAPID keys.
 */
class AddFcmColumnsToDevicePushSubscriptions extends Migration
{
    public function up()
    {
        // Add new columns for FCM
        $this->forge->addColumn('device_push_subscriptions', [
            'condominium_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'user_id',
            ],
            'fcm_token' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'condominium_id',
            ],
            'device_info' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => 'Flutter Mobile',
                'after'      => 'fcm_token',
            ],
            'platform' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'default'    => 'android',
                'after'      => 'device_info',
            ],
        ]);

        // Make existing Web Push fields nullable (they're not used by Flutter)
        $this->forge->modifyColumn('device_push_subscriptions', [
            'endpoint' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'p256dh_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'auth_key' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('device_push_subscriptions', ['condominium_id', 'fcm_token', 'device_info', 'platform']);
    }
}
