<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorInvitationsTable extends Migration
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
            'visitor_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'expected_arrival_date' => [
                'type'           => 'DATE',
            ],
            'notes' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'arrived', 'cancelled', 'expired'],
                'default'        => 'pending',
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
        
        // Optimización caseta: Listar rápido los esperados por día
        $this->forge->addKey(['condominium_id', 'expected_arrival_date']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('visitor_invitations', true);
    }

    public function down()
    {
        $this->forge->dropTable('visitor_invitations', true);
    }
}
