<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailsToQrCodes extends Migration
{
    public function up()
    {
        $fields = [
            'visit_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'vehicle_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'vehicle_plate' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ];
        
        $this->forge->addColumn('qr_codes', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('qr_codes', ['visit_type', 'vehicle_type', 'vehicle_plate']);
    }
}
