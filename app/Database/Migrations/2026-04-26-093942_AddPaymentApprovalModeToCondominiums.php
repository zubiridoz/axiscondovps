<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentApprovalModeToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'payment_approval_mode' => [
                'type'       => 'ENUM',
                'constraint' => ['manual', 'automatic'],
                'default'    => 'manual',
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'payment_approval_mode');
    }
}
