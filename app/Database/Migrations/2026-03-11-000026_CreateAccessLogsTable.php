<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAccessLogsTable extends Migration
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
                'null'           => true, // Puede ser visita a administración general
            ],
            'recorded_by' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'qr_code_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true, // Ingresos manuales no tienen QR
            ],
            'visitor_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true, // Opcional, enlace al catálogo
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['entry', 'exit'],
            ],
            'visitor_type' => [
                'type'           => 'ENUM',
                'constraint'     => ['pedestrian', 'vehicle'],
                'default'        => 'pedestrian',
            ],
            'visitor_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'plate_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'photo_url' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true, // Bitácora Intocable: Es el timestamp oficial del cruce
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Optimización caseta: Listar entradas de hoy
        $this->forge->addKey(['condominium_id', 'created_at']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('recorded_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('qr_code_id', 'qr_codes', 'id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('visitor_id', 'visitors', 'id', 'CASCADE', 'SET NULL');
        
        $this->forge->createTable('access_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('access_logs', true);
    }
}
