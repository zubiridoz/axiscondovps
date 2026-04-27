<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinancialTransactionsTable extends Migration
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
            'category_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['charge', 'credit'],
                'default'        => 'charge',
            ],
            'amount' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
            ],
            'description' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'due_date' => [
                'type'           => 'DATE',
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'partial', 'paid', 'cancelled'],
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
        
        // Optimización Tenant + Estado de cuenta
        $this->forge->addKey(['condominium_id', 'unit_id', 'status']);
        
        // No borrar cuotas si se deshabilita condómino (RESTRICT) para no afectar reportes contables
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('category_id', 'financial_categories', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->forge->createTable('financial_transactions', true);
    }

    public function down()
    {
        $this->forge->dropTable('financial_transactions', true);
    }
}
