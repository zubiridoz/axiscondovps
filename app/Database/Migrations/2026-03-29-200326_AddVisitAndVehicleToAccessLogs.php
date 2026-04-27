<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVisitAndVehicleToAccessLogs extends Migration
{
    public function up()
    {
        $fields = [
            'visit_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'visitor_type'
            ],
            'vehicle_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'visit_type'
            ]
        ];
        $this->forge->addColumn('access_logs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('access_logs', ['visit_type', 'vehicle_type']);
    }
}
