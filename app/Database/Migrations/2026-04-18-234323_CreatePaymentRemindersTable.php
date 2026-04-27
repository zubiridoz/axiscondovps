<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentRemindersTable extends Migration
{
    public function up()
    {
        // 1. payment_reminders table
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'trigger_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'trigger_value' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'message_title' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],
            'message_body' => [
                'type' => 'TEXT',
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payment_reminders', true);

        // 2. payment_reminder_logs table
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'reminder_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
            ],
            'sent_date' => [
                'type' => 'DATE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('reminder_id', 'payment_reminders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        // Add index to speed up duplicate checking
        $this->forge->addKey(['reminder_id', 'user_id', 'sent_date']);
        $this->forge->createTable('payment_reminder_logs', true);
    }

    public function down()
    {
        $this->forge->dropTable('payment_reminder_logs', true);
        $this->forge->dropTable('payment_reminders', true);
    }
}
