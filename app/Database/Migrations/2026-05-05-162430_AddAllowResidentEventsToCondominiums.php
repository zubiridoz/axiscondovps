<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAllowResidentEventsToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'allow_resident_events' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'allow_resident_posts'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'allow_resident_events');
    }
}
