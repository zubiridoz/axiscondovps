<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLateFeeTrackingToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'late_fee_applied' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'after' => 'status'
            ],
        ];

        $this->forge->addColumn('financial_transactions', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('financial_transactions', 'late_fee_applied');
    }
}
