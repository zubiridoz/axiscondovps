<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsStarredToTenantDocuments extends Migration
{
    public function up()
    {
        $fields = [
            'is_starred' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
            ],
        ];
        $this->forge->addColumn('tenant_documents', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('tenant_documents', 'is_starred');
    }
}
