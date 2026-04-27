<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOccupancyToUnits extends Migration
{
    public function up()
    {
        $fields = [
            'occupancy_type' => [
                'type'       => 'ENUM',
                'constraint' => ['owner_occupied', 'long_term_rent', 'short_term_rent', 'vacant'],
                'default'    => 'owner_occupied',
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('units', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('units', 'occupancy_type');
    }
}
