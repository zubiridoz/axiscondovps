<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFinancialSettingsToCondominiums extends Migration
{
    public function up()
    {
        $fields = [
            'owner_financial_access' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'unit_community',
                'null'       => false,
            ],
            'tenant_financial_access' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'none',
                'null'       => false,
            ],
            'show_delinquent_units' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'show_delinquency_amounts' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
        ];
        
        $this->forge->addColumn('condominiums', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('condominiums', [
            'owner_financial_access',
            'tenant_financial_access',
            'show_delinquent_units',
            'show_delinquency_amounts'
        ]);
    }
}
