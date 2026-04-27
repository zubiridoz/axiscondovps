<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeliveryFieldsToParcels extends Migration
{
    public function up()
    {
        $this->forge->addColumn('parcels', [
            'picked_up_by' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'delivered_at',
                'comment'    => 'User ID of resident who picked up',
            ],
            'picked_up_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
                'after'      => 'picked_up_by',
            ],
            'signature_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'picked_up_name',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('parcels', ['picked_up_by', 'picked_up_name', 'signature_url']);
    }
}
