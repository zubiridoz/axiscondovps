<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBillingSettingsToCondominiums extends Migration
{
    public function up()
    {
        $fields = [
            'is_billing_active' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
            'billing_start_date' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'billing_due_day' => [
                'type'       => 'INT',
                'constraint' => 2,
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('condominiums', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', ['is_billing_active', 'billing_start_date', 'billing_due_day']);
    }
}
