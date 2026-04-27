<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDocumentViewsTable extends Migration
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
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'document_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
            ],
            'user_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['view', 'download'],
                'default'    => 'view',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['condominium_id', 'document_id']);
        $this->forge->addKey('created_at');
        $this->forge->createTable('document_views', true);
    }

    public function down()
    {
        $this->forge->dropTable('document_views', true);
    }
}
