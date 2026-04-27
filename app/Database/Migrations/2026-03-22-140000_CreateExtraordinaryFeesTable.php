<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateExtraordinaryFeesTable extends Migration
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
                'constraint'     => 255,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'category_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'amount' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
            ],
            'expected_total' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
                'default'        => 0.00,
            ],
            'start_date' => [
                'type'           => 'DATE',
                'null'           => true,
            ],
            'due_date' => [
                'type'           => 'DATE',
                'null'           => true,
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
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('category_id', 'financial_categories', 'id', 'SET NULL', 'SET NULL');
        
        $this->forge->createTable('extraordinary_fees');
    }

    public function down()
    {
        $this->forge->dropTable('extraordinary_fees');
    }
}
