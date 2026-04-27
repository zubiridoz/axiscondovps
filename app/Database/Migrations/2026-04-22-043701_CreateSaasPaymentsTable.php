<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Tabla de log para pagos manuales del SaaS.
 * Registra pagos recibidos fuera de Stripe (efectivo, transferencia, depósito).
 */
class CreateSaasPaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'plan_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'payment_type' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'transfer', 'deposit'],
                'default'    => 'transfer',
            ],
            'reference' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'billing_cycle' => [
                'type'       => 'ENUM',
                'constraint' => ['monthly', 'yearly'],
                'default'    => 'monthly',
            ],
            'period_start' => [
                'type' => 'DATE',
            ],
            'period_end' => [
                'type' => 'DATE',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'recorded_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('condominium_id');
        $this->forge->addKey('created_at');
        $this->forge->createTable('saas_payments', true);
    }

    public function down()
    {
        $this->forge->dropTable('saas_payments', true);
    }
}
