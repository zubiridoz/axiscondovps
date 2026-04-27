<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnnouncementsTable extends Migration
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
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'created_by' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'content' => [
                'type'           => 'TEXT',
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['news', 'alert', 'maintenance'],
                'default'        => 'news',
            ],
            'is_active' => [
                'type'           => 'BOOLEAN',
                'default'        => true,
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
        
        // Optimización cronológica multi-tenant para el feed/muro
        $this->forge->addKey(['condominium_id', 'created_at']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('announcements', true);
    }

    public function down()
    {
        $this->forge->dropTable('announcements', true);
    }
}
