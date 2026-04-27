<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'first_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ],
            'last_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 100,
            ],
            'email' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
                'unique'         => true,
            ],
            'password_hash' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'phone' => [
                'type'           => 'VARCHAR',
                'constraint'     => 30,
                'null'           => true,
            ],
            'status' => [
                'type'           => 'ENUM',
                'constraint'     => ['active', 'inactive', 'banned'],
                'default'        => 'active',
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
        $this->forge->addKey('status');
        $this->forge->createTable('users', true);
    }

    public function down()
    {
        $this->forge->dropTable('users', true);
    }
}
