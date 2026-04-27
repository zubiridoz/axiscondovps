<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAmenityDocumentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'amenity_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'filename' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'file_size' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'file_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('amenity_id');
        $this->forge->addForeignKey('amenity_id', 'amenities', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('amenity_documents', true);
    }

    public function down()
    {
        $this->forge->dropTable('amenity_documents', true);
    }
}
