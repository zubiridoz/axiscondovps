<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ReconcileUnits extends BaseCommand
{
    protected $group       = 'Finance';
    protected $name        = 'finance:reconcile';
    protected $description = 'Reconcilia el estado financiero de todas las unidades de un condominio.';
    protected $usage       = 'finance:reconcile <condo_id>';
    protected $arguments   = [
        'condo_id' => 'ID del condominio',
    ];

    public function run(array $params)
    {
        $condoId = (int) ($params[0] ?? 0);
        if (!$condoId) {
            CLI::error('Uso: php spark finance:reconcile <condo_id>');
            return;
        }

        $db = \Config\Database::connect();
        
        $condo = $db->table('condominiums')->where('id', $condoId)->get()->getRowArray();
        if (!$condo) {
            CLI::error("Condominio {$condoId} no encontrado.");
            return;
        }
        CLI::write("Condominio: {$condo['name']} (ID: {$condoId})", 'cyan');

        \App\Services\TenantService::getInstance()->setTenantId($condoId);

        $units = $db->table('units')->where('condominium_id', $condoId)->orderBy('id', 'ASC')->get()->getResultArray();
        CLI::write("Total unidades: " . count($units), 'cyan');

        $reconciled = 0;
        foreach ($units as $unit) {
            $unitId = (int) $unit['id'];
            $initialBalance = (float) ($unit['initial_balance'] ?? 0);

            $db->transStart();

            // 1. Reset FIFO
            $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('type', 'charge')
                ->where('status !=', 'cancelled')
                ->update(['amount_paid' => 0, 'status' => 'pending']);

            // 2. Re-asignar pagos FIFO
            $payments = $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('type', 'credit')
                ->where('status', 'paid')
                ->where('deleted_at', null)
                ->orderBy('due_date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get()->getResultArray();

            foreach ($payments as $payment) {
                $amountToAllocate = (float) $payment['amount'];
                $pendingCharges = $db->table('financial_transactions')
                    ->where('unit_id', $unitId)
                    ->where('type', 'charge')
                    ->whereIn('status', ['pending', 'partial'])
                    ->where('deleted_at', null)
                    ->orderBy('due_date', 'ASC')
                    ->orderBy('created_at', 'ASC')
                    ->get()->getResultArray();

                foreach ($pendingCharges as $charge) {
                    if ($amountToAllocate <= 0.01) break;
                    $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
                    if ($debtRemaining <= 0.01) continue;
                    $applied = min($amountToAllocate, $debtRemaining);
                    $newPaid = (float) $charge['amount_paid'] + $applied;
                    $newStatus = ($newPaid >= ((float) $charge['amount'] - 0.01)) ? 'paid' : 'partial';
                    $db->table('financial_transactions')->where('id', $charge['id'])->update([
                        'amount_paid' => $newPaid, 'status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $amountToAllocate -= $applied;
                }
            }

            // 3. Apply floating credit (initial_balance)
            $creditRow = $db->table('financial_transactions')
                ->select('SUM(amount) as total_credits')
                ->where('unit_id', $unitId)->where('type', 'credit')->where('status', 'paid')
                ->get()->getRowArray();
            $totalCredits = (float) ($creditRow['total_credits'] ?? 0);

            $allocatedRow = $db->table('financial_transactions')
                ->select('SUM(amount_paid) as total_allocated')
                ->where('unit_id', $unitId)->where('type', 'charge')->where('status !=', 'cancelled')
                ->get()->getRowArray();
            $totalAllocated = (float) ($allocatedRow['total_allocated'] ?? 0);

            $floatingCredit = $totalCredits - $totalAllocated;
            if ($initialBalance < 0) {
                $floatingCredit += abs($initialBalance);
            }

            $appliedCount = 0;
            if ($floatingCredit > 0.01) {
                $pendingCharges = $db->table('financial_transactions')
                    ->where('unit_id', $unitId)->where('type', 'charge')
                    ->whereIn('status', ['pending', 'partial'])
                    ->orderBy('due_date', 'ASC')->orderBy('created_at', 'ASC')
                    ->get()->getResultArray();

                foreach ($pendingCharges as $charge) {
                    if ($floatingCredit <= 0.01) break;
                    $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
                    if ($debtRemaining <= 0) continue;
                    $applyAmount = min($floatingCredit, $debtRemaining);
                    $newPaid = (float) $charge['amount_paid'] + $applyAmount;
                    $newStatus = ($newPaid >= ((float) $charge['amount'] - 0.01)) ? 'paid' : 'partial';
                    $db->table('financial_transactions')->where('id', $charge['id'])->update([
                        'amount_paid' => $newPaid, 'status' => $newStatus, 'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                    $floatingCredit -= $applyAmount;
                    $appliedCount++;
                }
            }

            $db->transComplete();

            if ($db->transStatus() !== false) {
                $status = $appliedCount > 0 ? "crédito aplicado a {$appliedCount} cargo(s)" : 'ok';
                CLI::write("  ✓ {$unit['unit_number']}: {$status}", $appliedCount > 0 ? 'green' : 'white');
                $reconciled++;
            } else {
                CLI::write("  ✗ {$unit['unit_number']}: ERROR", 'red');
            }
        }

        CLI::write("\n✅ {$reconciled}/" . count($units) . " unidades reconciliadas.", 'green');
    }
}
