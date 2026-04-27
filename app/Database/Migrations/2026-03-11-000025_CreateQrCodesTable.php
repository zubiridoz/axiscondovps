<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQrCodesTable extends Migration
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
            'unit_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'created_by' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'visitor_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true, // Puede estar ligado o no a un visitante ya registrado
            ],
            'token' => [
                'type'           => 'VARCHAR',
                'constraint'     => 64,
                'unique'         => true,
            ],
            'visitor_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'valid_from' => [
                'type'           => 'DATETIME',
            ],
            'valid_until' => [
                'type'           => 'DATETIME',
            ],
            'usage_limit' => [
                'type'           => 'INT',
                'default'        => 1,
            ],
            'times_used' => [
                'type'           => 'INT',
                'default'        => 0,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'expired', 'revoked'],
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
        
        // Optimización mantenimiento/dashboard:
        $this->forge->addKey(['condominium_id', 'valid_from']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('visitor_id', 'visitors', 'id', 'CASCADE', 'SET NULL');
        
        $this->forge->createTable('qr_codes', true);
    }

    public function down()
    {
        $this->forge->dropTable('qr_codes', true);
    }
}
