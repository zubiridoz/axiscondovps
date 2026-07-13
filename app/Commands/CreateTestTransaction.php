<?php
namespace App\Commands;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateTestTransaction extends BaseCommand {
    protected $group = 'Debug';
    protected $name = 'debug:create-test-transaction';

    public function run(array $params) {
        $db = \Config\Database::connect();

        $condo = $db->table('condominiums')->get()->getRowArray();
        $unit = $db->table('units')->where('condominium_id', $condo['id'] ?? 0)->get()->getRowArray();
        $category = $db->table('financial_categories')->where('condominium_id', $condo['id'] ?? 0)->get()->getRowArray();

        if (!$condo || !$unit) {
            CLI::error("Falta inicializar la base de datos con los seeders.");
            return;
        }

        $transactionData = [
            'condominium_id' => $condo['id'],
            'unit_id' => $unit['id'],
            'category_id' => $category ? $category['id'] : 1,
            'type' => 'credit',
            'amount' => 1700.00,
            'description' => 'PAGO - COMPROBANTE APROBADO',
            'due_date' => date('Y-m-d'),
            'status' => 'paid',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $db->table('financial_transactions')->insert($transactionData);
        $insertId = $db->insertID();

        CLI::write("Transaccion de prueba creada con ID: " . $insertId, 'green');
    }
}
