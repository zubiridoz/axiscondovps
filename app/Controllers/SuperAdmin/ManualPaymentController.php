<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;

/**
 * ManualPaymentController
 *
 * Registra pagos manuales (efectivo/transferencia/depósito) para condominios
 * cuyo payment_method es 'manual'. Extiende automáticamente la vigencia del plan.
 *
 * Reglas de vigencia:
 *   - Si el plan aún está activo: el nuevo período se extiende desde plan_expires_at
 *   - Si está vencido: el nuevo período inicia desde hoy
 *   - Excepción: fechas manuales permitidas para migración inicial
 */
class ManualPaymentController extends BaseController
{
    private function db()
    {
        return \Config\Database::connect();
    }

    /**
     * Registrar un pago manual (AJAX).
     * POST /superadmin/payments/record
     */
    public function record()
    {
        $condoId     = (int) $this->request->getPost('condominium_id');
        $amount      = (float) $this->request->getPost('amount');
        $paymentType = $this->request->getPost('payment_type') ?: 'transfer';
        $reference   = trim((string) $this->request->getPost('reference'));
        $notes       = trim((string) $this->request->getPost('notes'));

        // Fechas manuales opcionales (solo para migración)
        $manualStart = $this->request->getPost('period_start') ?: null;
        $manualEnd   = $this->request->getPost('period_end') ?: null;

        // ── Validaciones ──
        if (!$condoId || $amount <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Condominio y monto son obligatorios.'
            ])->setStatusCode(422);
        }

        if (!in_array($paymentType, ['cash', 'transfer', 'deposit'])) {
            $paymentType = 'transfer';
        }

        $db = $this->db();

        $condo = $db->table('condominiums')->where('id', $condoId)->get()->getRowArray();
        if (!$condo) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Condominio no encontrado.'
            ])->setStatusCode(404);
        }

        if (empty($condo['plan_id'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El condominio no tiene un plan asignado. Asigna uno primero.'
            ])->setStatusCode(422);
        }

        $plan = $db->table('plans')->where('id', $condo['plan_id'])->get()->getRowArray();
        $billingCycle = $condo['billing_cycle'] ?? 'monthly';

        // ── Calcular período ──
        if ($manualStart && $manualEnd) {
            // Modo migración: fechas definidas manualmente
            $periodStart = $manualStart;
            $periodEnd   = $manualEnd;
        } else {
            // Modo normal: cálculo automático
            $now       = date('Y-m-d');
            $expiresAt = $condo['plan_expires_at'] ? date('Y-m-d', strtotime($condo['plan_expires_at'])) : null;

            // Si aún está activo (no vencido), extender desde plan_expires_at
            if ($expiresAt && $expiresAt >= $now) {
                $periodStart = $expiresAt;
            } else {
                // Vencido o sin fecha: iniciar desde hoy
                $periodStart = $now;
            }

            $periodEnd = $billingCycle === 'yearly'
                ? date('Y-m-d', strtotime($periodStart . ' +1 year'))
                : date('Y-m-d', strtotime($periodStart . ' +1 month'));
        }

        // ── Insertar registro de pago ──
        $userId = session()->get('user_id') ?? session()->get('user')['id'] ?? 0;

        $db->table('saas_payments')->insert([
            'condominium_id' => $condoId,
            'plan_id'        => $condo['plan_id'],
            'amount'         => $amount,
            'payment_type'   => $paymentType,
            'reference'      => $reference ?: null,
            'billing_cycle'  => $billingCycle,
            'period_start'   => $periodStart,
            'period_end'     => $periodEnd,
            'notes'          => $notes ?: null,
            'recorded_by'    => $userId,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // ── Actualizar vigencia del condominio ──
        $db->table('condominiums')->where('id', $condoId)->update([
            'plan_expires_at' => $periodEnd . ' 23:59:59',
            'status'          => 'active',
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        $planName = $plan['name'] ?? 'Plan';
        $periodEndFormatted = date('d/m/Y', strtotime($periodEnd));

        return $this->response->setJSON([
            'success' => true,
            'message' => "Pago registrado. {$planName} vigente hasta {$periodEndFormatted}.",
        ]);
    }

    /**
     * Historial de pagos manuales de un condominio (AJAX).
     * GET /superadmin/payments/:id/history
     */
    public function history($condoId = null)
    {
        if (!$condoId) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID requerido.'])->setStatusCode(400);
        }

        $payments = $this->db()->table('saas_payments sp')
            ->select('sp.*, u.first_name, u.last_name, p.name AS plan_name')
            ->join('users u', 'u.id = sp.recorded_by', 'left')
            ->join('plans p', 'p.id = sp.plan_id', 'left')
            ->where('sp.condominium_id', $condoId)
            ->orderBy('sp.created_at', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        return $this->response->setJSON([
            'success'  => true,
            'payments' => $payments,
        ]);
    }
}
