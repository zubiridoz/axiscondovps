<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitNotesTable extends Migration
{
    public function up()
    {
        // En CI4 las llaves primarias suelen ser INT(11) UNSIGNED. 
        // Vamos a asegurar el match exacto para evitar el error de foreign key.
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'unit_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'condominium_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'note' => [
                'type' => 'TEXT',
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
        $this->forge->createTable('unit_notes', true);
    }

    public function down()
    {
        $this->forge->dropTable('unit_notes', true);
    }
}
