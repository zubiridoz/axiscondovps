<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterUnitIdNullableInTickets extends Migration
{
    public function up()
    {
        $fields = [
            'unit_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('tickets', $fields);
    }

    public function down()
    {
        $fields = [
            'unit_id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'null' => false,
            ],
        ];
        $this->forge->modifyColumn('tickets', $fields);
    }
}
