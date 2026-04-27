<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFloorAndFeeToUnits extends Migration
{
    public function up()
    {
        $fields = [
            'floor' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'unit_number'
            ],
            'maintenance_fee' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
                'after'      => 'type'
            ]
        ];
        $this->forge->addColumn('units', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'floor');
        $this->forge->dropColumn('units', 'maintenance_fee');
    }
}
