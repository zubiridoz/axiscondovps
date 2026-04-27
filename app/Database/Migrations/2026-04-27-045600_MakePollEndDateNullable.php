<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakePollEndDateNullable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('polls', [
            'end_date' => [
                'type'    => 'DATETIME',
                'null'    => true,
                'default' => null,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('polls', [
            'end_date' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);
    }
}
