<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CleanupMonthlyCharges extends BaseCommand
{
    protected $group       = 'Finance';
    protected $name        = 'charges:cleanup';
    protected $description = 'Removes monthly charge run logs older than 12 months.';

    public function run(array $params)
    {
        CLI::write('Starting cleanup of old monthly charge runs...', 'yellow');
        
        $db = \Config\Database::connect();
        
        // Calcular fecha hace 12 meses exactos (para respetar el requerimiento "NO borres registros del mes actual ni anterior")
        // La condición de "> 12 meses" elimina registros muy viejos pero mantiene un historial para debugging de 1 año.
        $cutoffDate = date('Y-m-d H:i:s', strtotime('-12 months'));

        $db->table('monthly_charge_runs')
           ->where('created_at <', $cutoffDate)
           ->delete();

        $affectedRows = $db->affectedRows();

        CLI::write("Cleanup complete. Removed {$affectedRows} old records.", 'green');
    }
}
