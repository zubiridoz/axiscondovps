<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePollOptionsAndVotesTables extends Migration
{
    public function up()
    {
        // 1. poll_options table
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'poll_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'option_text' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('poll_id');
        $this->forge->addForeignKey('poll_id', 'polls', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('poll_options', true);

        // 2. poll_votes table
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'poll_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'poll_option_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'user_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['poll_id', 'user_id']); 
        $this->forge->addForeignKey('poll_id', 'polls', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('poll_option_id', 'poll_options', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        
        // Un usuario sólo puede votar una vez por encuesta general (a menos que se cambie regla después)
        $this->forge->addUniqueKey(['poll_id', 'user_id']);
        
        $this->forge->createTable('poll_votes', true);
    }

    public function down()
    {
        $this->forge->dropTable('poll_votes', true);
        $this->forge->dropTable('poll_options', true);
    }
}
