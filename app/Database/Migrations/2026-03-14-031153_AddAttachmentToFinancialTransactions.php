<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttachmentToFinancialTransactions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('financial_transactions', [
            'attachment' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'description'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('financial_transactions', 'attachment');
    }
}
