<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAreaAndIndivisoToUnits extends Migration
{
    public function up()
    {
        $fields = [
            'area' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
                'after'      => 'type'
            ],
            'indiviso_percentage' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'area'
            ]
        ];
        $this->forge->addColumn('units', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'area');
        $this->forge->dropColumn('units', 'indiviso_percentage');
    }
}
