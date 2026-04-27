<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinancialCategoriesTable extends Migration
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
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ],
            'description' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['income', 'expense'],
                'default'        => 'income',
            ],
            'is_system' => [
                'type'           => 'BOOLEAN',
                'default'        => false,
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
        
        $this->forge->addKey('condominium_id');
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('financial_categories', true);
    }

    public function down()
    {
        $this->forge->dropTable('financial_categories', true);
    }
}
