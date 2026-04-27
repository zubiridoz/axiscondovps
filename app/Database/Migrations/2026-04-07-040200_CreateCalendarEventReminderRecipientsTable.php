<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCalendarEventReminderRecipientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'reminder_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'staff_member_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('reminder_id', 'calendar_event_reminders', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('calendar_event_reminder_recipients', true);
    }

    public function down()
    {
        $this->forge->dropTable('calendar_event_reminder_recipients', true);
    }
}
