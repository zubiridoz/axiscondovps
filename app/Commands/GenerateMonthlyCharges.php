<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateMonthlyCharges extends BaseCommand
{
    protected $group       = 'Finance';
    protected $name        = 'charges:generate';
    protected $description = 'Generates monthly maintenance charges for all active condominiums.';

    public function run(array $params)
    {
        CLI::write('Starting monthly charge generation...', 'green');
        
        $db = \Config\Database::connect();
        $condominiums = $db->table('condominiums')->where('is_billing_active', 1)->get()->getResultArray();

        if (empty($condominiums)) {
            CLI::write('No active condominiums found.', 'yellow');
            return;
        }

        $service = new \App\Services\MonthlyChargeService();
        $successCount = 0;
        $failedCount = 0;

        foreach ($condominiums as $condo) {
            // Validar si el condominio tiene timezone propio (opcional, si no, usa el del server)
            $timezone = $condo['timezone'] ?? date_default_timezone_get();
            date_default_timezone_set($timezone);

            try {
                $service->generateIfNotExists((int)$condo['id'], 'cron');
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
                log_message('error', "[CRON GenerateMonthlyCharges] Error para condo {$condo['id']}: " . $e->getMessage());
                CLI::write("Error processing condominium ID: {$condo['id']} - " . $e->getMessage(), 'red');
            }
        }

        // Restaurar timezone default del sistema al terminar
        date_default_timezone_set(date_default_timezone_get());

        CLI::write("Process complete. Processed: {$successCount}, Failed: {$failedCount}", 'green');
    }
}
