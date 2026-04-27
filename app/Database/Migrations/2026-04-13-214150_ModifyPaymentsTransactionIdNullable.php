<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyPaymentsTransactionIdNullable extends Migration
{
    public function up()
    {
        // Drop the existing foreign key first
        $this->db->query('ALTER TABLE payments DROP FOREIGN KEY payments_transaction_id_foreign');
        
        // Make transaction_id nullable (for generic payment proof uploads without a specific transaction)
        $this->db->query('ALTER TABLE payments MODIFY transaction_id BIGINT UNSIGNED NULL DEFAULT NULL');
        
        // Re-add the foreign key with ON DELETE SET NULL
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT payments_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES financial_transactions(id) ON DELETE SET NULL ON UPDATE RESTRICT');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE payments DROP FOREIGN KEY payments_transaction_id_foreign');
        $this->db->query('ALTER TABLE payments MODIFY transaction_id BIGINT UNSIGNED NOT NULL');
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT payments_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES financial_transactions(id) ON DELETE RESTRICT ON UPDATE RESTRICT');
    }
}
