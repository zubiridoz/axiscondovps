<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTicketCommentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ticket_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'condominium_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'message' => [
                'type'           => 'TEXT',
            ],
            // 'reply' = visible to resident, 'internal' = only admin/staff
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['reply', 'internal'],
                'default'        => 'reply',
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['ticket_id', 'type']);
        $this->forge->addKey('condominium_id');
        $this->forge->addForeignKey('ticket_id', 'tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('ticket_comments', true);
    }

    public function down()
    {
        $this->forge->dropTable('ticket_comments', true);
    }
}
