<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateResidentInvitationsTable extends Migration
{
    public function up()
    {
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
            'unit_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'token' => [
                'type'       => 'VARCHAR',
                'constraint' => '64',
                'unique'     => true,
            ],
            'invitation_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'pending',
            ],
            'invited_by' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'invited_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'accepted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id', true);
        
        // Custom Indexes
        $this->forge->addKey(['condominium_id', 'email'], false, false, 'idx_condo_email');
        $this->forge->addKey('token', false, false, 'idx_token');
        $this->forge->addKey('invitation_status', false, false, 'idx_status');
        $this->forge->addKey('expires_at', false, false, 'idx_expires');

        // Foreign keys for data integrity
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('unit_id', 'units', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('invited_by', 'users', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('resident_invitations');
    }

    public function down()
    {
        $this->forge->dropTable('resident_invitations');
    }
}
