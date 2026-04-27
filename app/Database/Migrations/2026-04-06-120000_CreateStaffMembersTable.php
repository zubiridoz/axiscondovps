<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'condominium_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'first_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'last_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'staff_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
                'default'    => 'security',
                'comment'    => 'security, maintenance, other',
            ],
            'device_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'comment'    => 'Optional link to security device credentials',
            ],
            'photo_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
            ],
            'id_document_url' => [
                'type'       => 'VARCHAR',
                'constraint' => 500,
                'null'       => true,
                'default'    => null,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'active',
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('condominium_id');
        $this->forge->addKey('staff_type');
        $this->forge->addKey('device_id');
        $this->forge->addKey('deleted_at');

        $this->forge->createTable('staff_members', true);
    }

    public function down()
    {
        $this->forge->dropTable('staff_members', true);
    }
}
