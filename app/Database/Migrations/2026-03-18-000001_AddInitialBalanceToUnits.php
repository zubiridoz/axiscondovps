<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInitialBalanceToUnits extends Migration
{
    public function up()
    {
        $this->forge->addColumn('units', [
            'initial_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'null'       => false,
                'after'      => 'maintenance_fee',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'initial_balance');
    }
}
