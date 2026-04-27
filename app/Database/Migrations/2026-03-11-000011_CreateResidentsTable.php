<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResidentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'unit_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['owner', 'tenant'],
                'default'        => 'owner',
            ],
            'is_active' => [
                'type'           => 'BOOLEAN',
                'default'        => true,
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
        
        // Optimizaciones Tenant: Búsqueda rápida de directorio
        $this->forge->addKey(['condominium_id', 'user_id']);
        $this->forge->addKey(['condominium_id', 'unit_id']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('residents', true);
    }

    public function down()
    {
        $this->forge->dropTable('residents', true);
    }
}
