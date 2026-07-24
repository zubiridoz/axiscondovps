<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterPollsTitleToText extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('polls', [
            'title' => [
                'type' => 'TEXT',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('polls', [
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
        ]);
    }
}
