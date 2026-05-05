<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddResidentViewCommentsToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'resident_view_comments' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'allow_post_comments',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'resident_view_comments');
    }
}
