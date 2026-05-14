<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIssueDateToFinancialTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'issue_date' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'description'
            ]
        ];
        
        $this->forge->addColumn('financial_transactions', $fields);
        
        // Populate historical data: set issue_date equal to date of created_at
        // If created_at is null, use due_date
        $this->db->query("
            UPDATE financial_transactions 
            SET issue_date = COALESCE(DATE(created_at), due_date)
        ");
    }

    public function down()
    {
        $this->forge->dropColumn('financial_transactions', 'issue_date');
    }
}
