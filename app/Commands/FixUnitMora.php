<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixUnitMora extends BaseCommand
{
    protected $group       = 'Finance';
    protected $name        = 'finance:fix-mora';
    protected $description = 'Cancela mora errónea de una unidad y reconcilia su estado financiero.';
    protected $usage       = 'finance:fix-mora <condo_id> <unit_number>';
    protected $arguments   = [
        'condo_id'    => 'ID del condominio',
        'unit_number' => 'Número de unidad (ej: A-100)',
    ];

    public function run(array $params)
    {
        $condoId    = (int) ($params[0] ?? 0);
        $unitNumber = $params[1] ?? '';

        if (!$condoId || !$unitNumber) {
            CLI::error('Uso: php spark finance:fix-mora <condo_id> <unit_number>');
            return;
        }

        $db = \Config\Database::connect();

        // 1. Encontrar la unidad
        $unit = $db->table('units')
            ->where('condominium_id', $condoId)
            ->where('unit_number', $unitNumber)
            ->get()->getRowArray();

        if (!$unit) {
            CLI::error("Unidad '{$unitNumber}' no encontrada en condominio {$condoId}.");
            return;
        }

        $unitId = (int) $unit['id'];
        CLI::write("Unidad encontrada: {$unitNumber} (ID: {$unitId}), Saldo Inicial: {$unit['initial_balance']}", 'cyan');

        // 2. Buscar moras auto-generadas pendientes
        $moraCat = $db->table('financial_categories')
            ->where('condominium_id', $condoId)
            ->where('name', 'Cargo por Mora')
            ->get()->getRowArray();

        $moraCharges = $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('source', 'auto')
            ->whereIn('status', ['pending', 'partial'])
            ->get()->getResultArray();

        if (empty($moraCharges)) {
            CLI::write('No se encontraron moras pendientes para cancelar.', 'yellow');
        } else {
            CLI::write('Moras pendientes encontradas:', 'yellow');
            foreach ($moraCharges as $mora) {
                CLI::write("  - ID: {$mora['id']} | Monto: \${$mora['amount']} | Fecha: {$mora['due_date']} | Desc: {$mora['description']}", 'yellow');
                
                // Cancelar (reversa lógica)
                $db->table('financial_transactions')->where('id', $mora['id'])->update([
                    'status'     => 'cancelled',
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                CLI::write("    → Cancelada (status = cancelled)", 'green');
            }
        }

        // 3. Establecer contexto de tenant
        \App\Services\TenantService::getInstance()->setTenantId($condoId);

        // 4. Reconciliar estado financiero (equivalente a syncUnitFinancialState)
        CLI::write('Reconciliando estado financiero...', 'blue');
        
        $db->transStart();

        // Reset FIFO
        $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->update(['amount_paid' => 0, 'status' => 'pending']);

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
                    'amount_paid' => $newPaid,
                    'status'      => $newStatus,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                $amountToAllocate -= $applied;
            }
        }

        // Apply floating credit (initial_balance)
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

        if ($floatingCredit > 0.01) {
            CLI::write("Crédito flotante disponible: \${$floatingCredit}", 'cyan');
            $pendingCharges = $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('type', 'charge')
                ->whereIn('status', ['pending', 'partial'])
                ->orderBy('due_date', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get()->getResultArray();

            foreach ($pendingCharges as $charge) {
                if ($floatingCredit <= 0.01) break;
                $debtRemaining = (float) $charge['amount'] - (float) $charge['amount_paid'];
                if ($debtRemaining <= 0) continue;
                $applyAmount = min($floatingCredit, $debtRemaining);
                $newPaid = (float) $charge['amount_paid'] + $applyAmount;
                $newStatus = ($newPaid >= ((float) $charge['amount'] - 0.01)) ? 'paid' : 'partial';
                $db->table('financial_transactions')->where('id', $charge['id'])->update([
                    'amount_paid' => $newPaid,
                    'status'      => $newStatus,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
                CLI::write("  → Aplicado \${$applyAmount} a cargo #{$charge['id']} ({$charge['description']})", 'green');
                $floatingCredit -= $applyAmount;
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            CLI::error('ERROR: La transacción falló.');
            return;
        }

        // 5. Mostrar estado final
        $finalCharges = $db->table('financial_transactions')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->orderBy('due_date', 'ASC')
            ->get()->getResultArray();

        CLI::write("\nEstado final de cargos:", 'blue');
        foreach ($finalCharges as $fc) {
            $statusLabel = strtoupper($fc['status']);
            CLI::write("  [{$statusLabel}] #{$fc['id']} | \${$fc['amount']} (pagado: \${$fc['amount_paid']}) | {$fc['description']} | Vence: {$fc['due_date']}", 
                $fc['status'] === 'paid' ? 'green' : 'yellow');
        }

        CLI::write("\n✅ Reconciliación completada exitosamente.", 'green');
    }
}
