<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SubscriptionCheck extends BaseCommand
{
    protected $group       = 'Billing';
    protected $name        = 'subscription:check';
    protected $description = 'Verifica el estado de las suscripciones en periodo de gracia y suspende condominios con pagos fallidos vencidos.';

    public function run(array $params)
    {
        CLI::write('Iniciando verificación de suscripciones SaaS...', 'yellow');
        $db = \Config\Database::connect();

        // 1. Encontrar aquellos en "past_due" cuyo periodo de gracia haya expirado
        $now = date('Y-m-d H:i:s');
        $expiredCondos = $db->table('condominiums')
            ->select('id, name')
            ->where('subscription_status', 'past_due')
            ->where('grace_until <', $now)
            ->where('grace_until IS NOT NULL')
            ->where('deleted_at IS NULL')
            ->get()
            ->getResultArray();

        $countExpired = count($expiredCondos);

        if ($countExpired > 0) {
            CLI::write("Se encontraron {$countExpired} condominios con periodo de gracia expirado.", 'red');
            
            foreach ($expiredCondos as $condo) {
                CLI::write("Suspendiendo condominio #{$condo['id']} - {$condo['name']}");
                
                // Suspender el condominio en base a las reglas
                $db->table('condominiums')->where('id', $condo['id'])->update([
                    'subscription_status' => 'suspended',
                    'status'              => 'suspended', // Bloquea acceso general también si es necesario
                    'updated_at'          => date('Y-m-d H:i:s')
                ]);
            }
        } else {
            CLI::write('No se encontraron condominios con gracia expirada.', 'green');
        }

        CLI::write('Proceso finalizado.', 'green');
    }
}
