<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToParcels extends Migration
{
    public function up()
    {
        // Add photo_url, quantity, parcel_type columns
        $this->forge->addColumn('parcels', [
            'photo_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'courier',
            ],
            'quantity' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 1,
                'after'      => 'photo_url',
            ],
            'parcel_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Paquete',
                'after'      => 'quantity',
            ],
        ]);

        // Alter status ENUM to support new values
        $db = \Config\Database::connect();
        $db->query("ALTER TABLE parcels MODIFY COLUMN `status` ENUM('at_gate','delivered_to_resident','returned','pending') DEFAULT 'at_gate'");
    }

    public function down()
    {
        $this->forge->dropColumn('parcels', ['photo_url', 'quantity', 'parcel_type']);

        $db = \Config\Database::connect();
        $db->query("ALTER TABLE parcels MODIFY COLUMN `status` ENUM('at_gate','delivered_to_resident') DEFAULT 'at_gate'");
    }
}
