<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MakeUnitIdNullableInTransactions extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('financial_transactions', [
            'unit_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('financial_transactions', [
            'unit_id' => [
                'type'     => 'BIGINT',
                'unsigned' => true,
                'null'     => false,
            ]
        ]);
    }
}
