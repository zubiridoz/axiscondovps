<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSignatureFieldsToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'signature_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'signature_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'signature_image');
        $this->forge->dropColumn('condominiums', 'signature_name');
    }
}
