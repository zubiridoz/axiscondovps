<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
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
            'transaction_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'amount' => [
                'type'           => 'DECIMAL',
                'constraint'     => '10,2',
            ],
            'payment_method' => [
                'type'           => 'ENUM',
                'constraint'     => ['cash', 'transfer', 'card', 'check'],
                'default'        => 'transfer',
            ],
            'reference_code' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
                'null'           => true,
            ],
            'proof_url' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => true,
            ],
            'notes' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['pending', 'approved', 'rejected'],
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
        
        // Optimización cronológica por tenant
        $this->forge->addKey(['condominium_id', 'created_at']);
        
        // Optimización contabilidad por unidad
        $this->forge->addKey(['condominium_id', 'unit_id']);
        
        // Evitar orfandad manteniendo históricos de pago financieros
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'RESTRICT', 'RESTRICT');
        $this->forge->addForeignKey('transaction_id', 'financial_transactions', 'id', 'RESTRICT', 'RESTRICT');
        
        $this->forge->createTable('payments', true);
    }

    public function down()
    {
        $this->forge->dropTable('payments', true);
    }
}
