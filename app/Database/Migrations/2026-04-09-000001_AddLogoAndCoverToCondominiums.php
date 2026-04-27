<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLogoAndCoverToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'logo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'address',
            ],
            'cover_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'logo',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', ['logo', 'cover_image']);
    }
}
