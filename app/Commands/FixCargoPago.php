<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;

class FixCargoPago extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'fix:cargopago';
    protected $description = 'Fix pending charges that have a matching paid credit';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $db->query("
            UPDATE financial_transactions ft
            INNER JOIN financial_transactions ft2
                ON ft2.unit_id = ft.unit_id
                AND ft2.amount = ft.amount
                AND ft2.type = 'credit'
                AND ft2.status = 'paid'
                AND DATE(ft2.created_at) = DATE(ft.created_at)
                AND ft2.source = 'manual'
            SET ft.status = 'paid'
            WHERE ft.type = 'charge'
            AND ft.status = 'pending'
            AND ft.source = 'manual'
        ");
        echo "Done. Affected rows: " . $db->affectedRows() . PHP_EOL;
    }
}
