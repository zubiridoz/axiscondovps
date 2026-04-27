<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTicketHashLength extends Migration
{
    public function up()
    {
        $fields = [
            'ticket_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 24,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('tickets', $fields);
    }

    public function down()
    {
        $fields = [
            'ticket_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 12,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('tickets', $fields);
    }
}
