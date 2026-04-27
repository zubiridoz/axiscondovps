<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePollsTable extends Migration
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
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'start_date' => [
                'type'           => 'DATETIME',
            ],
            'end_date' => [
                'type'           => 'DATETIME',
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
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        
        // Optimización cronológica multi-tenant
        $this->forge->addKey(['condominium_id', 'created_at']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('polls', true);
    }

    public function down()
    {
        $this->forge->dropTable('polls', true);
    }
}
