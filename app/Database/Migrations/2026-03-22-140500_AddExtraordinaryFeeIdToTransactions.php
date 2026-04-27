<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExtraordinaryFeeIdToTransactions extends Migration
{
    public function up()
    {
        $fields = [
            'extraordinary_fee_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'unit_id',
            ],
        ];

        $this->forge->addColumn('financial_transactions', $fields);
        
        // Agregar llave foránea
        // Primero aseguramos usar la conexión
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE `financial_transactions` ADD CONSTRAINT `fk_financial_transactions_extraordinary_fees` FOREIGN KEY (`extraordinary_fee_id`) REFERENCES `extraordinary_fees`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;');
    }

    public function down()
    {
        $db = \Config\Database::connect();
        $db->query('ALTER TABLE `financial_transactions` DROP FOREIGN KEY `fk_financial_transactions_extraordinary_fees`;');
        $this->forge->dropColumn('financial_transactions', 'extraordinary_fee_id');
    }
}
