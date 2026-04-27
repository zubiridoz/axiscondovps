<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAmenitiesTable extends Migration
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
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'description' => [
                'type'           => 'TEXT',
                'null'           => true,
            ],
            'capacity' => [
                'type'           => 'INT',
                'null'           => true,
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
        
        $this->forge->addKey('condominium_id');
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('amenities', true);
    }

    public function down()
    {
        $this->forge->dropTable('amenities', true);
    }
}
