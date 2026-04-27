<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDevicePushSubscriptionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'device_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'endpoint' => [
                'type'           => 'TEXT',
            ],
            'p256dh_key' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'auth_key' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        $this->forge->addKey('user_id');
        
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('device_id', 'devices', 'id', 'CASCADE', 'SET NULL');
        
        $this->forge->createTable('device_push_subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('device_push_subscriptions', true);
    }
}
