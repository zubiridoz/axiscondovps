<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToFinancialTransactions extends Migration
{
    public function up()
    {
        // Ignore errors if index already exists
        $this->db->query('ALTER TABLE `financial_transactions` ADD INDEX `idx_condo_due_date` (`condominium_id`, `due_date`)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `financial_transactions` DROP INDEX `idx_condo_due_date`');
    }
}
