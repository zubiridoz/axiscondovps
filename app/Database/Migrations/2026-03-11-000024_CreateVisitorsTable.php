<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVisitorsTable extends Migration
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
            'full_name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 150,
            ],
            'document_type' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true, // Ej: DNI, Pasaporte
            ],
            'document_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],
            'phone' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'is_banned' => [
                'type'           => 'BOOLEAN',
                'default'        => false,
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
        
        // Optimización caseta: Autocompletado de visitantes frecuentes
        $this->forge->addKey(['condominium_id', 'document_number']);
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('visitors', true);
    }

    public function down()
    {
        $this->forge->dropTable('visitors', true);
    }
}
