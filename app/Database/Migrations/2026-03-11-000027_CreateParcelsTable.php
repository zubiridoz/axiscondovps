<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateParcelsTable extends Migration
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
            'received_by' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'courier' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100, // Ej: Amazon, FedEx
            ],
            'tracking_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['at_gate', 'delivered_to_resident'],
                'default'        => 'at_gate',
            ],
            'delivered_at' => [
                'type'           => 'DATETIME',
                'null'           => true, // Momento en que el residente recoge
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
        
        $this->forge->addKey(['condominium_id', 'status', 'created_at']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('received_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('parcels', true);
    }

    public function down()
    {
        $this->forge->dropTable('parcels', true);
    }
}
