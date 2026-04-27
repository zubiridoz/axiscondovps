<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitsTable extends Migration
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
            'section_id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'null'           => true,
            ],
            'unit_number' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
            ],
            'type' => [
                'type'           => 'ENUM',
                'constraint'     => ['house', 'apartment', 'commercial', 'lot'],
                'default'        => 'house',
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
        
        // Regla única: No puede haber mismo depto en misma torre/sección en el mismo condominio
        $this->forge->addUniqueKey(['condominium_id', 'section_id', 'unit_number']);
        
        // Optimización Tenant General
        $this->forge->addKey('condominium_id');
        
        $this->forge->addForeignKey('condominium_id', 'condominiums', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('section_id', 'sections', 'id', 'CASCADE', 'SET NULL');
        
        $this->forge->createTable('units', true);
    }

    public function down()
    {
        $this->forge->dropTable('units', true);
    }
}
