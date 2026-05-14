<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLateFeeSettingsToCondominiums extends Migration
{
    public function up()
    {
        $fields = [
            'late_fee_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'late_fee_type' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'fixed',
            ],
            'late_fee_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'late_fee_percentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0,
            ],
            'late_fee_max_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'late_fee_grace_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'late_fee_grace_days' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'late_fee_categories' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('condominiums', $fields);
    }

    public function down()
    {
        $fields = [
            'late_fee_enabled',
            'late_fee_type',
            'late_fee_amount',
            'late_fee_percentage',
            'late_fee_max_amount',
            'late_fee_grace_enabled',
            'late_fee_grace_days',
            'late_fee_categories'
        ];
        $this->forge->dropColumn('condominiums', $fields);
    }
}
