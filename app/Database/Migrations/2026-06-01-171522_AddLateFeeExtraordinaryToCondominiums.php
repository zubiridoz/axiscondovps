<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLateFeeExtraordinaryToCondominiums extends Migration
{
    public function up()
    {
        $this->forge->addColumn('condominiums', [
            'late_fee_on_extraordinary' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'late_fee_categories'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'late_fee_on_extraordinary');
    }
}
