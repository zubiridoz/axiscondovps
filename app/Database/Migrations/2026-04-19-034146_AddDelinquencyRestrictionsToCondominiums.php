<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDelinquencyRestrictionsToCondominiums extends Migration
{
    public function up()
    {
        $fields = [
            'restrict_qr_delinquent' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'show_delinquency_amounts'
            ],
            'restrict_amenities_delinquent' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'null'       => false,
                'after'      => 'restrict_qr_delinquent'
            ],
        ];

        $this->forge->addColumn('condominiums', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'restrict_qr_delinquent');
        $this->forge->dropColumn('condominiums', 'restrict_amenities_delinquent');
    }
}
