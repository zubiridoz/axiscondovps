<?php

namespace App\Services;

use CodeIgniter\Database\Exceptions\DatabaseException;

class MonthlyChargeService
{
    /**
     * Genera cargos mensuales automáticos para el mes actual si no existen.
     * Es completamente idempotente (lock por UNIQUE constraint).
     */
    public function generateIfNotExists(int $condominiumId, string $source = 'cron'): void
    {
        $db = \Config\Database::connect();
        
        $condo = $db->table('condominiums')->where('id', $condominiumId)->get()->getRowArray();
        if (!$condo || !($condo['is_billing_active'] ?? false)) {
            return;
        }

        $billingStart = $condo['billing_start_date'] ?? null;
        if (!$billingStart) {
            return;
        }

        $condoTz = $condo['timezone'] ?? date_default_timezone_get();
        $tz = new \DateTimeZone($condoTz);
        $now = new \DateTime('now', $tz);

        $currentMonth = $now->format('Y-m');
        $startMonth = date('Y-m', strtotime($billingStart));

        if ($currentMonth < $startMonth) {
            return; // Aún no inicia la facturación
        }

        // 1. Intentar tomar el lock (o recuperar uno fallido o trabado) vía INSERT ... ON DUPLICATE KEY UPDATE
        // Recuperamos si está failed, o si lleva en processing más de 15 minutos (caída del servidor/timeout)
        $sql = "INSERT INTO monthly_charge_runs (condominium_id, period, source, status, created_at, updated_at) 
                VALUES (?, ?, ?, 'processing', NOW(), NOW())
                ON DUPLICATE KEY UPDATE 
                    source = IF(status = 'failed' OR (status = 'processing' AND updated_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)), VALUES(source), source),
                    updated_at = IF(status = 'failed' OR (status = 'processing' AND updated_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)), NOW(), updated_at),
                    status = IF(status = 'failed' OR (status = 'processing' AND updated_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)), 'processing', status)";
        
        $db->query($sql, [$condominiumId, $currentMonth, $source]);

        // Verificamos si realmente logramos obtener el lock (status = 'processing' e insertado/actualizado por nosotros en este instante)
        $run = $db->table('monthly_charge_runs')
                  ->where('condominium_id', $condominiumId)
                  ->where('period', $currentMonth)
                  ->get()->getRowArray();

        // Si el estado no es processing, o si alguien más lo está procesando (concurrencia extrema), 
        // asumimos que no debemos continuar. Aunque el lock de tabla de mysql ya nos protegió.
        if (!$run || $run['status'] !== 'processing') {
            return; // Ya fue procesado con éxito o está siendo procesado por otro hilo
        }

        // En este punto TENEMOS EL LOCK EXCLUSIVO LÓGICO para este condominio y período.
        $db->transStart();

        try {
            $category = $db->table('financial_categories')
                ->where('condominium_id', $condominiumId)
                ->where('name', 'Cuota de Mantenimiento')
                ->get()->getRowArray();

            if (!$category) {
                // No hay categoría, marcamos como éxito sin generar nada
                $db->table('monthly_charge_runs')->where('id', $run['id'])->update(['status' => 'success', 'executed_at' => date('Y-m-d H:i:s')]);
                $db->transComplete();
                return;
            }
            $catId = $category['id'];

            $units = $db->table('units')
                ->where('condominium_id', $condominiumId)
                ->where('maintenance_fee IS NOT NULL')
                ->where('maintenance_fee >', 0)
                ->get()->getResultArray();

            if (empty($units)) {
                $db->table('monthly_charge_runs')->where('id', $run['id'])->update(['status' => 'success', 'executed_at' => date('Y-m-d H:i:s')]);
                $db->transComplete();
                return;
            }

            $dueDay = (int) ($condo['billing_due_day'] ?? 10);
            $mesesLargos = [
                'January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril',
                'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto',
                'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'
            ];
            $monthNameLargo = $now->format('F');
            $monthNameEs = ($mesesLargos[$monthNameLargo] ?? $monthNameLargo) . ' ' . $now->format('Y');
            $dueDateStr = $currentMonth . '-' . str_pad($dueDay, 2, '0', STR_PAD_LEFT);

            $batchData = [];
            $affectedUnits = [];
            
            foreach ($units as $u) {
                // Validación secundaria por seguridad: no insertar si ya existe cobro de la categoría en el mes
                $txExists = $db->table('financial_transactions')
                    ->where('unit_id', $u['id'])
                    ->where('category_id', $catId)
                    ->where('type', 'charge')
                    ->like('due_date', $currentMonth, 'after')
                    ->countAllResults();

                if ($txExists == 0) {
                    $batchData[] = [
                        'condominium_id' => $condominiumId,
                        'unit_id' => $u['id'],
                        'category_id' => $catId,
                        'type' => 'charge',
                        'amount' => $u['maintenance_fee'],
                        'description' => 'Cuota de Mantenimiento ' . $monthNameEs,
                        'due_date' => $dueDateStr,
                        'status' => 'pending',
                        'source' => 'auto',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                    $affectedUnits[] = $u['id'];
                }
            }

            if (!empty($batchData)) {
                $db->table('financial_transactions')->insertBatch($batchData);
            }

            // Post-procesamiento DENTRO de la transacción principal
            foreach ($affectedUnits as $uId) {
                $this->applyFloatingCredit((int) $uId);
            }

            // Marcar corrida como exitosa
            $db->table('monthly_charge_runs')->where('id', $run['id'])->update(['status' => 'success', 'executed_at' => date('Y-m-d H:i:s')]);
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            // Notificaciones push (llamadas externas) FUERA de la transacción
            foreach ($affectedUnits as $uId) {
                $residents = $db->table('residents')
                    ->select('DISTINCT(user_id) as user_id')
                    ->where('unit_id', $uId)
                    ->where('condominium_id', $condominiumId)
                    ->where('is_active', 1)
                    ->where('user_id IS NOT NULL')
                    ->get()->getResultArray();

                foreach ($residents as $res) {
                    \App\Models\Tenant\NotificationModel::notify(
                        $condominiumId,
                        (int) $res['user_id'],
                        'new_charge',
                        'Nueva cuota generada',
                        'Se ha generado la cuota de mantenimiento de ' . $monthNameEs . '.',
                        ['unit_id' => $uId, 'tipo' => 'nueva_cuota'],
                        true
                    );
                }
            }

        } catch (\Throwable $e) {
            // Si algo falla, hacemos rollback y marcamos como failed
            $db->transRollback();
            $db->table('monthly_charge_runs')->where('id', $run['id'])->update(['status' => 'failed', 'updated_at' => date('Y-m-d H:i:s')]);
            log_message('error', '[MonthlyChargeService] Error para condo ' . $condominiumId . ': ' . $e->getMessage());
        }
    }

    /**
     * Rutina automática que reabsorbe el saldo a favor flotante sobre los cargos nuevos de una unidad
     */
    public function applyFloatingCredit(int $unitId): void
    {
        $db = \Config\Database::connect();

        $unit = $db->table('units')->select('initial_balance')->where('id', $unitId)->get()->getRowArray();
        $initialBalance = (float) ($unit['initial_balance'] ?? 0);

        $creditRow = $db->table('financial_transactions')
            ->select('SUM(amount) as total_credits')
            ->where('unit_id', $unitId)
            ->where('type', 'credit')
            ->where('status', 'paid')
            ->get()->getRowArray();
        $totalCredits = (float) ($creditRow['total_credits'] ?? 0);

        $chargeAllocatedRow = $db->table('financial_transactions')
            ->select('SUM(amount_paid) as total_allocated')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->get()->getRowArray();
        $totalAllocated = (float) ($chargeAllocatedRow['total_allocated'] ?? 0);

        $floatingCredit = $totalCredits - $totalAllocated;
        if ($initialBalance < 0) {
            $floatingCredit += abs($initialBalance);
        }

        if ($floatingCredit <= 0.01) {
            return;
        }

        $pendingCharges = $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->whereIn('status', ['pending', 'partial'])
            ->orderBy('due_date', 'ASC')
            ->orderBy('created_at', 'ASC')
            ->get()->getResultArray();

        foreach ($pendingCharges as $charge) {
            if ($floatingCredit <= 0.01)
                break;

            $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
            if ($debtRemaining <= 0)
                continue;

            $applyAmount = min($floatingCredit, $debtRemaining);
            $newPaid = (float) $charge['amount_paid'] + $applyAmount;
            $newStatus = ($newPaid >= (float) $charge['amount'] - 0.01) ? 'paid' : 'partial';

            $db->table('financial_transactions')->where('id', $charge['id'])->update([
                'amount_paid' => $newPaid,
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            $floatingCredit -= $applyAmount;
        }
    }
}
