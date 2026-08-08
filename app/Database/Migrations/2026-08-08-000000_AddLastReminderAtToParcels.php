<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLastReminderAtToParcels extends Migration
{
    public function up()
    {
        $this->forge->addColumn('parcels', [
            'last_reminder_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null,
            ],
            'reminder_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'default' => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('parcels', 'last_reminder_at');
        $this->forge->dropColumn('parcels', 'reminder_count');
    }
}
