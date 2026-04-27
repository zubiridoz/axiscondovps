<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCondominiumsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'subscription_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'address' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'currency' => [
                'type'           => 'VARCHAR',
                'constraint'     => 3,
                'default'        => 'USD',
            ],
            'timezone' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'default'        => 'America/Mexico_City',
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'suspended'],
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
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        $this->forge->addKey('status');
        
        // FK protect: Si la suscripción se borra, no permitir si hay condominios operando (por seguridad)
        $this->forge->addForeignKey('subscription_id', 'subscriptions', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->forge->createTable('condominiums', true);
    }

    public function down()
    {
        $this->forge->dropTable('condominiums', true);
    }
}
