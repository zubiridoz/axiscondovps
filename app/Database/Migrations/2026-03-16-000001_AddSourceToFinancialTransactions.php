<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds a 'source' column to financial_transactions to distinguish
 * between manually created records (Nuevo Registro) and auto-generated
 * charges (billing system).
 */
class AddSourceToFinancialTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('financial_transactions', [
            'source' => [
                'type'       => 'ENUM',
                'constraint' => ['manual', 'auto'],
                'default'    => 'manual',
                'after'      => 'status',
            ],
        ]);

        // Mark existing records (created by activateBilling) as 'auto'
        $this->db->query("UPDATE financial_transactions SET source = 'auto' WHERE description LIKE 'Cuota de Mantenimiento%'");
    }

    public function down()
    {
        $this->forge->dropColumn('financial_transactions', 'source');
    }
}
