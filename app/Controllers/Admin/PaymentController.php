<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\PaymentModel;
use App\Models\Tenant\FinancialTransactionModel;

/**
 * PaymentController
 * 
 * Gestión de la recepción y conciliación de pagos vinculados al Condominio.
 */
class PaymentController extends BaseController
{
    /**
     * Lista todos los pagos registrados (historial)
     */
    public function index()
    {
        $paymentModel = new PaymentModel();
        $payments = $paymentModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $payments]);
    }

    /**
     * Registra un pago y salda una transacción
     */
    public function registerPayment()
    {
        // El cajero o residente reporta un pago
        $transactionId = $this->request->getPost('transaction_id');
        $amount        = $this->request->getPost('amount');
        $unitId        = $this->request->getPost('unit_id');

        $transactionModel = new FinancialTransactionModel();
        $paymentModel     = new PaymentModel();

        // Verificamos que la deuda exista EN ESTE CONDOMINIO
        $transaction = $transactionModel->find($transactionId);
        
        if (!$transaction) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Transacción no encontrada']);
        }
        
        if ($transaction['status'] === 'paid') {
            return $this->response->setJSON(['status' => 400, 'error' => 'Esta deuda ya fue pagada']);
        }

        // 1. Registramos el recibo de pago
        $paymentData = [
            'unit_id'        => $unitId,
            'transaction_id' => $transactionId,
            'amount'         => $amount,
            'payment_method' => $this->request->getPost('payment_method') ?? 'cash', // card, transfer, cash
            'reference_code' => $this->request->getPost('reference_code'),
            'notes'          => $this->request->getPost('notes'),
            'status'         => 'completed' // completed, pending_verification
            // condominium_id inyectado globalmente
        ];
        
        $paymentId = $paymentModel->insert($paymentData);

        // 2. Actualizamos el estado de la transacción original a Pagada
        // En una app real de contabilidad deberías validar si el abono cubre el total de la deuda
        if ($amount >= $transaction['amount']) {
            $transactionModel->update($transactionId, ['status' => 'paid']);
        } else {
            // Lógica de pagos parciales si lo permites ('partial')
            $transactionModel->update($transactionId, ['status' => 'partial']);
        }

        return $this->response->setJSON(['status' => 200, 'message' => 'Pago procesado exitosamente', 'payment_id' => $paymentId]);
    }

    /**
     * Estado de Cuenta por Unidad
     */
    public function accountStatement($unitId = null)
    {
        if (!$unitId) return $this->response->setJSON(['status' => 400, 'error' => 'Unidad requerida']);

        $transactionModel = new FinancialTransactionModel();
        
        // Obtenemos solo los cargos de esta unidad generados en el actual Condominio
        $transactions = $transactionModel->where('unit_id', $unitId)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();

        // Calculamos deuda total
        $totalDebt = 0;
        foreach ($transactions as $txn) {
            if ($txn['type'] === 'charge' && in_array($txn['status'], ['pending', 'partial'])) {
                // Faltaría restar los abonos si existieran
                $totalDebt += $txn['amount'];
            }
        }

        return $this->response->setJSON([
            'status' => 200,
            'data'   => [
                'unit_id'      => $unitId,
                'total_debt'   => $totalDebt,
                'transactions' => $transactions
            ]
        ]);
    }
}
