<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethodToFinancialTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
                'after'      => 'amount',
            ],
        ];
        $this->forge->addColumn('financial_transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('financial_transactions', 'payment_method');
    }
}
