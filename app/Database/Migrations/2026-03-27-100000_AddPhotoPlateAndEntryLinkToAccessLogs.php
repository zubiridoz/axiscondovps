<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotoPlateAndEntryLinkToAccessLogs extends Migration
{
    public function up()
    {
        $fields = [
            'photo_plate_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'photo_url',
            ],
            'entry_log_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'photo_plate_url',
            ],
            'gate_number' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'entry_log_id',
            ],
            'notes' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'gate_number',
            ],
        ];

        $this->forge->addColumn('access_logs', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('access_logs', ['photo_plate_url', 'entry_log_id', 'gate_number', 'notes']);
    }
}
