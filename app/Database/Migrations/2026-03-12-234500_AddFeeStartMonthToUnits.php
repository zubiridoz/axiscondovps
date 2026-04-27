<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFeeStartMonthToUnits extends Migration
{
    public function up()
    {
        $fields = [
            'fee_start_month' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'null'       => true,
                'after'      => 'maintenance_fee'
            ],
        ];
        $this->forge->addColumn('units', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'fee_start_month');
    }
}
