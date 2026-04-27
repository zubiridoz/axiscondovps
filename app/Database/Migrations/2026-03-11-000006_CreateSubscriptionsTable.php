<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSubscriptionsTable extends Migration
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
            'plan_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'starts_at' => [
                'type'           => 'DATE',
            ],
            'expires_at' => [
                'type'           => 'DATE',
                'null'           => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'expired', 'cancelled'],
                'default'        => 'active',
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
        
        // Índices para búsquedas de vigencia
        $this->forge->addKey(['user_id', 'status']);
        $this->forge->addKey('expires_at');
        
        // Llaves Foráneas (Se usa RESTRICT para proteger la integridad de la facturación y los inquilinos)
        $this->forge->addForeignKey('user_id', 'users', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('plan_id', 'plans', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->forge->createTable('subscriptions', true);
    }

    public function down()
    {
        $this->forge->dropTable('subscriptions', true);
    }
}
