<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCalendarEventRemindersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'minutes_before' => [
                'type'     => 'INT',
                'unsigned' => true,
                'default'  => 30,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('event_id', 'calendar_events', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('calendar_event_reminders', true);
    }

    public function down()
    {
        $this->forge->dropTable('calendar_event_reminders', true);
    }
}
