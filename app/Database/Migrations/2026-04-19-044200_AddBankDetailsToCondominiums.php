<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankDetailsToCondominiums extends Migration
{
    public function up()
    {
        $fields = [
            'bank_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'restrict_amenities_delinquent'
            ],
            'bank_clabe' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'bank_name'
            ],
            'bank_rfc' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'bank_clabe'
            ],
        ];

        $this->forge->addColumn('condominiums', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', 'bank_name');
        $this->forge->dropColumn('condominiums', 'bank_clabe');
        $this->forge->dropColumn('condominiums', 'bank_rfc');
    }
}
