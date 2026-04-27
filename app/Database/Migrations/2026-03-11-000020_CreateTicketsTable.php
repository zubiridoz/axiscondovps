<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketsTable extends Migration
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
            'reported_by' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'subject' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'description' => [
                'type'           => 'TEXT',
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['open', 'in_progress', 'resolved', 'closed'],
                'default'        => 'open',
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
        
        // Optimización Tenant para listado de incidencias pendientes
        $this->forge->addKey(['condominium_id', 'status', 'created_at']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('reported_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('tickets', true);
    }

    public function down()
    {
        $this->forge->dropTable('tickets', true);
    }
}
