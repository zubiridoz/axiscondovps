<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\FinancialTransactionModel;
use App\Models\Tenant\PaymentModel;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\CondominiumModel;

class FinanceController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $message
        ])->setStatusCode($status);
    }

    /**
     * Get the current pending balance for the resident's units
     */
    public function balance()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $residentModel = new ResidentModel();
        $myUnits = $residentModel->where('user_id', $userId)->findAll();

        if (empty($myUnits)) {
            return $this->respondSuccess(['total_balance' => 0, 'units' => []]);
        }

        $unitIds = array_column($myUnits, 'unit_id');
        $transactionModel = new FinancialTransactionModel();
        $unitModel = new UnitModel();

        $unitsDetails = [];
        $globalBalance = 0;

        foreach ($myUnits as $res) {
            $unit = $unitModel->find($res['unit_id']);
            if (!$unit) continue;

            $transactions = $transactionModel->where('unit_id', $unit['id'])->findAll();
            $debt = 0;

            foreach ($transactions as $txn) {
                if ($txn['type'] === 'charge' && in_array($txn['status'], ['pending', 'partial'])) {
                    $debt += (float) $txn['amount'];
                }
            }

            $globalBalance += $debt;
            $unitsDetails[] = [
                'unit_id' => $unit['id'],
                'unit_number' => $unit['unit_number'],
                'balance' => $debt
            ];
        }

        return $this->respondSuccess([
            'total_balance' => $globalBalance,
            'units' => $unitsDetails
        ]);
    }

    /**
     * Get past payments for the resident's units
     */
    public function payments()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $residentModel = new ResidentModel();
        $myUnits = $residentModel->where('user_id', $userId)->findAll();
        
        if (empty($myUnits)) {
            return $this->respondSuccess(['payments' => []]);
        }

        $unitIds = array_column($myUnits, 'unit_id');
        $paymentModel = new PaymentModel();

        $payments = $paymentModel->whereIn('unit_id', $unitIds)
                                 ->orderBy('created_at', 'DESC')
                                 ->findAll(50); // Get last 50 payments

        return $this->respondSuccess([
            'payments' => $payments
        ]);
    }

    /**
     * Get statement (summary of transactions and payments)
     */
    public function statement()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $residentModel = new ResidentModel();
        $myUnits = $residentModel->where('user_id', $userId)->findAll();
        
        if (empty($myUnits)) {
            return $this->respondSuccess(['statement' => []]);
        }

        $unitIds = array_column($myUnits, 'unit_id');
        $transactionModel = new FinancialTransactionModel();

        $transactions = $transactionModel->whereIn('unit_id', $unitIds)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll(100);

        return $this->respondSuccess([
            'statement' => $transactions
        ]);
    }

    /**
     * Devuelve el historial de comprobantes subidos por el residente ("Mis Comprobantes")
     */
    public function receiptsHistory()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        $month = (int) ($this->request->getGet('month') ?: 0);
        $year  = (int) ($this->request->getGet('year') ?: 0);

        $db = \Config\Database::connect();
        
        $builder = $db->table('payments p')
            ->select('p.id, p.amount, p.status, p.payment_method, p.proof_url as receipt_path, p.created_at')
            ->select('IF(p.status = "approved" AND cats.name IS NOT NULL, cats.name, p.notes) as concept')
            ->join('financial_transactions ft', 'ft.id = p.transaction_id', 'left')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('p.unit_id', $resident['unit_id'])
            ->where('p.condominium_id', $tenantId)
            ->where('p.deleted_at IS NULL');

        if ($month > 0 && $year > 0) {
            $builder->where('MONTH(p.created_at)', $month)
                    ->where('YEAR(p.created_at)', $year);
        }

        $receipts = $builder->orderBy('p.created_at', 'DESC')->get()->getResultArray();

        foreach ($receipts as &$rcpt) {
            $rcpt['is_official'] = false;
             if (!empty($rcpt['receipt_path'])) {
                $decoded = json_decode($rcpt['receipt_path'], true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $fileName = basename($decoded[0]);
                } else {
                    $fileName = basename($rcpt['receipt_path']);
                }
                
                $url = base_url('api/v1/finance/file/' . $fileName);
                if (isset($_SERVER['HTTP_HOST']) && strpos($url, 'localhost') !== false) {
                    $url = preg_replace('/localhost(:\d+)?/', $_SERVER['HTTP_HOST'], $url);
                }
                $rcpt['receipt_path'] = $url;
            }
        }

        return $this->respondSuccess($receipts);
    }

    /**
     * Upload a payment proof for validation by admin
     */
    public function uploadPaymentProof()
    {
        $userId = $this->request->userId ?? null;
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        if (!$userId) return $this->respondError('No autenticado', 401);

        $unitId = $this->request->getPost('unit_id');
        $amount = $this->request->getPost('amount') ?: 0;
        $method = $this->request->getPost('payment_method') ?: 'transfer';
        $reference = $this->request->getPost('reference_code') ?: '';
        $notes = $this->request->getPost('notes') ?: '';

        if (!$unitId) {
            return $this->respondError('Faltan campos requeridos (unit_id)', 422);
        }

        $residentModel = new ResidentModel();
        // Verify user actually belongs to this unit
        $isResident = $residentModel->where('user_id', $userId)->where('unit_id', $unitId)->first();
        if (!$isResident) {
            return $this->respondError('No tienes acceso a esta unidad', 403);
        }

        $file = $this->request->getFile('proof');
        if (!$file || !$file->isValid()) {
            return $this->respondError('Comprobante no proporcionado o inválido', 422);
        }

        $uploadPath = WRITEPATH . 'uploads/payments/' . $tenantId . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);
        
        $condoModel = new CondominiumModel();
        $condominium = $condoModel->find($tenantId);
        $approvalMode = $condominium['payment_approval_mode'] ?? 'manual';
        
        $db = \Config\Database::connect();
        $paymentStatus = ($approvalMode === 'automatic') ? 'approved' : 'pending';

        $paymentModel = new PaymentModel();
        $paymentId = $paymentModel->insert([
            'condominium_id' => $tenantId,
            'unit_id' => $unitId,
            'amount' => $amount,
            'payment_method' => $method,
            'reference_code' => $reference,
            'proof_url' => 'payments/' . $tenantId . '/' . $newName,
            'notes' => $notes,
            'status' => $paymentStatus // 'approved' or 'pending' depending on settings
        ]);

        $resident = $residentModel->where('user_id', $userId)->first();
        $unit = $db->table('units')->where('id', $unitId)->get()->getRowArray();
        $unitName = $unit ? $unit['unit_number'] : 'Desconocida';
        
        $userObj = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $uploaderName = $userObj ? ($userObj['first_name'] . ' ' . $userObj['last_name']) : 'Un residente';

        if ($approvalMode === 'automatic') {
            $methodMap = [
                'transfer' => 'Transferencia Bancaria',
                'cash' => 'Efectivo',
                'card' => 'Tarjeta',
                'check' => 'Cheque',
            ];
            $displayMethod = $methodMap[$method] ?? 'Transferencia Bancaria';

            // Create credit transaction
            $creditData = [
                'condominium_id' => $tenantId,
                'unit_id' => $unitId,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'PAGO - AUTO APROBADO',
                'due_date' => date('Y-m-d'),
                'status' => 'paid',
                'payment_method' => $displayMethod,
                'attachment' => 'payments/' . $tenantId . '/' . $newName,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $catRow = $db->table('financial_categories')
                ->where('condominium_id', $tenantId)
                ->where('name', 'Cuota de Mantenimiento')
                ->get()->getRowArray();
            if ($catRow) {
                $creditData['category_id'] = $catRow['id'];
            }

            $db->table('financial_transactions')->insert($creditData);
            $newTxId = $db->insertID();

            // Link to payment
            $paymentModel->update($paymentId, ['transaction_id' => $newTxId]);

            // Try to resolve oldest pending charges if any
            $pendingCharges = $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('condominium_id', $tenantId)
                ->where('type', 'charge')
                ->whereIn('status', ['pending', 'partial'])
                ->orderBy('due_date', 'ASC')
                ->get()->getResultArray();

            $remainingAmount = (float) $amount;
            foreach ($pendingCharges as $charge) {
                if ($remainingAmount <= 0) break;

                $chargeAmount = (float) $charge['amount'];
                $paidSoFar = (float) ($charge['amount_paid'] ?? 0);
                $unpaid = $chargeAmount - $paidSoFar;

                $apply = min($unpaid, $remainingAmount);
                $remainingAmount -= $apply;

                $newPaid = $paidSoFar + $apply;
                $newStatus = ($newPaid >= ($chargeAmount - 0.01)) ? 'paid' : 'partial';

                $db->table('financial_transactions')->where('id', $charge['id'])->update([
                    'status' => $newStatus,
                    'amount_paid' => $newPaid,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Notify Resident
            \App\Models\Tenant\NotificationModel::notify(
                $tenantId, 
                $userId, 
                'payment_status', 
                'Pago aplicado automáticamente', 
                'Tu comprobante fue procesado y el pago ha sido aplicado a tu saldo.',
                ['action_url' => 'app/finances']
            );

            // Notify admins (auto-approved context)
            $admins = $db->table('user_condominium_roles ucr')
                ->select('ucr.user_id as id')
                ->join('roles r', 'ucr.role_id = r.id')
                ->where('ucr.condominium_id', $tenantId)
                ->whereIn('r.name', ['admin', 'super_admin'])
                ->get()->getResultArray();
            
            foreach ($admins as $admin) {
                \App\Models\Tenant\NotificationModel::notify(
                    $tenantId, 
                    $admin['id'], 
                    'payment_status', 
                    'Pago Auto Aprobado', 
                    $uploaderName . ' subió un comprobante para ' . $unitName . ' y fue aprobado automáticamente.',
                    ['action_url' => 'admin/finanzas/pagos-por-unidad/' . $unit['hash_id']]
                );
            }

            return $this->respondSuccess([
                'message' => 'Comprobante subido y pago aplicado automáticamente',
                'payment_id' => $paymentId
            ]);
        } else {
            // Notificar a todos los administradores del condominio
            $admins = $db->table('user_condominium_roles ucr')
                ->select('ucr.user_id as id')
                ->join('roles r', 'ucr.role_id = r.id')
                ->where('ucr.condominium_id', $tenantId)
                ->whereIn('r.name', ['admin', 'super_admin'])
                ->get()->getResultArray();
            
            foreach ($admins as $admin) {
                \App\Models\Tenant\NotificationModel::notify(
                    $tenantId, 
                    $admin['id'], 
                    'payment_status', 
                    'Comprobante Subido', 
                    $uploaderName . ' subió un comprobante de pago para ' . $unitName,
                    ['action_url' => 'admin/finanzas/pagos-por-unidad/' . $unit['hash_id']]
                );
            }

            return $this->respondSuccess([
                'message' => 'Comprobante subido y registrado como pendiente',
                'payment_id' => $paymentId
            ]);
        }
    }

    /**
     * View global community transactions (if allowed)
     */
    public function communityTransactions()
    {
        $userId = $this->request->userId ?? null;
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        if (!$userId) return $this->respondError('No autenticado', 401);

        $db = \Config\Database::connect();
        $isAdmin = false;
        $pivot = $db->table('user_condominium_roles ucr')
            ->select('roles.name as role_name')
            ->join('roles', 'roles.id = ucr.role_id', 'left')
            ->where('ucr.user_id', $userId)
            ->where('ucr.condominium_id', $tenantId)
            ->get()->getRowArray();
            
        if ($pivot) {
            $roleName = strtolower($pivot['role_name'] ?? '');
            if (in_array($roleName, ['admin', 'super_admin', 'owner', 'super admin', 'superadmin'])) {
                $isAdmin = true;
            }
        }

        if (!$isAdmin) {
            $residentModel = new ResidentModel();
            $resident = $residentModel->where('user_id', $userId)->first();
            if (!$resident) return $this->respondError('Residente no encontrado', 404);
        }

        $condoModel = new CondominiumModel();
        $condo = $condoModel->find($tenantId);

        $type = $resident['type']; // 'owner' or 'tenant'

        $hasAccess = false;
        if ($type === 'owner' && $condo['owner_financial_access'] === 'unit_community') {
            $hasAccess = true;
        } else if ($type === 'tenant' && $condo['tenant_financial_access'] === 'unit_community') {
            $hasAccess = true;
        }

        if (!$hasAccess) {
            return $this->respondError('No tienes acceso para consultar finanzas de la comunidad', 403);
        }
        $month = (int) ($this->request->getGet('month') ?: 0);
        $year  = (int) ($this->request->getGet('year') ?: 0);

        $db = \Config\Database::connect();
        $builder = $db->table('financial_transactions ft')
            ->select('ft.id, cats.type, ft.amount, ft.description, ft.due_date as date, ft.status, ft.attachment as proof_url')
            ->select('IFNULL(cats.name, "General") as category')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('ft.deleted_at IS NULL');

        if ($month > 0 && $year > 0) {
            $startDate = sprintf('%04d-%02d-01', $year, $month);
            $endDate   = date('Y-m-t', strtotime($startDate));
            $builder->where('ft.due_date >=', $startDate)
                    ->where('ft.due_date <=', $endDate);
        }

        $transactions = $builder->orderBy('ft.due_date', 'DESC')
                                ->orderBy('ft.created_at', 'DESC')
                                ->get()->getResultArray();

        // Normalizar type para Flutter (income/expense)
        foreach ($transactions as &$mov) {
            $mov['amount'] = (float) $mov['amount'];
            if ($mov['type'] === 'credit') $mov['type'] = 'income';
            
            // Adjuntar full URL de comprobante
            if (!empty($mov['proof_url'])) {
                // Parsear posible arreglo JSON en el registro de attachment
                $decoded = json_decode($mov['proof_url'], true);
                if (is_array($decoded) && count($decoded) > 0) {
                    $fileName = basename($decoded[0]);
                } else {
                    $fileName = basename($mov['proof_url']);
                }
                
                $url = base_url('api/v1/finance/file/' . $fileName);
                
                // Si la app consume mediante IP local, evitar que se mande localhost (causando loopback al emulador)
                if (isset($_SERVER['HTTP_HOST']) && strpos($url, 'localhost') !== false) {
                    $url = preg_replace('/localhost(:\d+)?/', $_SERVER['HTTP_HOST'], $url);
                }
                
                $mov['proof_url'] = $url;
            }
        }

        return $this->respondSuccess([
            'community_transactions' => $transactions
        ]);
    }

    /**
     * Get delinquent units (morosidad) (if enabled)
     */
    public function delinquentUnits()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $condoModel = new CondominiumModel();
        $condo = $condoModel->find($tenantId);

        if (!$condo || !$condo['show_delinquent_units']) {
            return $this->respondError('La lista de morosos no está habilitada por la administración', 403);
        }

        $showAmounts = (bool) $condo['show_delinquency_amounts'];

        // Get units in debt
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT u.id, u.unit_number, SUM(t.amount) as debt
            FROM units u
            JOIN financial_transactions t ON t.unit_id = u.id
            WHERE t.type = 'charge' AND t.status IN ('pending', 'partial') AND t.deleted_at IS NULL AND u.deleted_at IS NULL
            GROUP BY u.id
            HAVING debt > 0
            ORDER BY u.unit_number ASC
        ");

        $delinquentList = $query->getResultArray();

        // Remove amounts if not allowed
        if (!$showAmounts) {
            foreach ($delinquentList as &$item) {
                unset($item['debt']);
            }
        }

        return $this->respondSuccess([
            'show_amounts' => $showAmounts,
            'delinquency_list' => $delinquentList
        ]);
    }

    /**
     * GET /api/v1/finance/file/{filename}
     * Sirve archivos financieros (comprobantes) desde writable/uploads/financial/
     */
    public function serveFinanceFile(string $fileName)
    {
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        
        // Buscar en todas las carpetas posibles de comprobantes
        $possiblePaths = [
            WRITEPATH . 'uploads/financial/' . $fileName,
        ];
        
        // También buscar en subcarpetas de payments/{tenantId}/
        $paymentsDir = WRITEPATH . 'uploads/payments/';
        if (is_dir($paymentsDir)) {
            foreach (scandir($paymentsDir) as $subDir) {
                if ($subDir === '.' || $subDir === '..') continue;
                $possiblePaths[] = $paymentsDir . $subDir . '/' . $fileName;
            }
        }
        
        $filePath = null;
        foreach ($possiblePaths as $p) {
            if (is_file($p)) {
                $filePath = $p;
                break;
            }
        }

        if (!$filePath) {
            return $this->response->setStatusCode(404)->setJSON(['status' => 'error', 'message' => 'Archivo no encontrado: ' . $fileName]);
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    // =====================================================================
    //  ENDPOINTS CONSUMIDOS POR FLUTTER (api/v1/resident/finances/...)
    // =====================================================================

    /**
     * GET /api/v1/resident/finances?month=X&year=Y
     * Devuelve balance + lista de transacciones de la unidad del residente.
     * Este es el endpoint que alimenta la pestaña "Mi Unidad" de la app.
     */
    public function myUnitFinances()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $condo = $condoModel->find($tenantId);

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();

        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        // Verificar acceso financiero
        $type = $resident['type'] ?? 'tenant';
        $accessLevel = ($type === 'owner')
            ? ($condo['owner_financial_access'] ?? 'none')
            : ($condo['tenant_financial_access'] ?? 'none');

        if ($accessLevel === 'none') {
            return $this->respondSuccess([
                'balance'  => 0,
                'items'    => [],
                'unit'     => null,
                'message'  => 'La administración no ha habilitado el acceso financiero',
            ]);
        }

        $unitId = $resident['unit_id'];
        $unitModel = new UnitModel();
        $unit = $unitModel->find($unitId);

        if (!$unit) {
            return $this->respondSuccess(['balance' => 0, 'items' => [], 'unit' => null]);
        }

        // Parámetros opcionales de mes/año
        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year')  ?: date('Y'));

        $db = \Config\Database::connect();

        // Calcular balance real: initial_balance + cargos - créditos
        // Positivo = deuda, Negativo = saldo a favor
        $initialBalance = (float) ($unit['initial_balance'] ?? 0);

        $chargesRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();
        $totalCharges = (float) ($chargesRow['amount'] ?? 0);

        $creditsRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('unit_id', $unitId)
            ->where('type', 'credit')
            ->where('status !=', 'cancelled')
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();
        $totalCredits = (float) ($creditsRow['amount'] ?? 0);

        $balance = $initialBalance + $totalCharges - $totalCredits;
        // Invertimos signo para Flutter: negativo = deuda, positivo = a favor
        $balance = -1 * $balance;

        // Obtener transacciones del mes seleccionado
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $transactions = $db->table('financial_transactions ft')
            ->select('ft.id, ft.type, ft.amount, ft.description, ft.due_date, ft.status, ft.payment_method, ft.attachment, ft.created_at, IFNULL(cats.name, "General") as concept')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.unit_id', $unitId)
            ->where('ft.deleted_at IS NULL')
            ->groupStart()
                ->where('ft.due_date >=', $startDate)
                ->where('ft.due_date <=', $endDate)
            ->groupEnd()
            ->orderBy('ft.due_date', 'ASC')
            ->orderBy('ft.created_at', 'ASC')
            ->get()->getResultArray();

        // Formatear para Flutter
        $items = [];
        foreach ($transactions as $t) {
            $items[] = [
                'id'           => (int) $t['id'],
                'concept'      => $t['concept'] ?: ($t['type'] === 'charge' ? 'Cuota de Mantenimiento' : 'Pago'),
                'amount'       => (float) $t['amount'],
                'due_date'     => $t['due_date'],
                'status'       => $t['status'],
                'type'         => $t['type'],
                'description'  => $t['description'],
                'payment_method' => $t['payment_method'],
                'receipt_path' => $t['attachment'],
                'created_at'   => $t['created_at'],
            ];
        }

        // Determinar estado de cuenta:
        // a_favor       → saldo negativo (créditos > cargos)
        // sin_adeudos   → no debe ninguna cuota (0 cargos pending/partial)
        // al_corriente  → solo debe cuotas que aún no vencen
        // moroso        → debe 1+ cuotas vencidas (due_date < hoy)
        $today = date('Y-m-d');
        $rawBalance = $initialBalance + $totalCharges - $totalCredits; // positivo = deuda

        if ($rawBalance < -0.01) {
            $accountStatus = 'a_favor';
            $overdueCount = 0;
        } else {
            // Contar cargos pendientes/parciales
            $pendingCount = (int) $db->table('financial_transactions')
                ->where('unit_id', $unitId)
                ->where('type', 'charge')
                ->whereIn('status', ['pending', 'partial'])
                ->where('deleted_at IS NULL')
                ->countAllResults();

            if ($pendingCount === 0) {
                $accountStatus = 'sin_adeudos';
                $overdueCount = 0;
            } else {
                // Contar cuotas vencidas (due_date < hoy)
                $overdueCount = (int) $db->table('financial_transactions')
                    ->where('unit_id', $unitId)
                    ->where('type', 'charge')
                    ->whereIn('status', ['pending', 'partial'])
                    ->where('due_date <', $today)
                    ->where('deleted_at IS NULL')
                    ->countAllResults();

                $accountStatus = ($overdueCount > 0) ? 'moroso' : 'al_corriente';
            }
        }

        return $this->respondSuccess([
            'balance'        => $balance,
            'account_status' => $accountStatus,
            'overdue_count'  => $overdueCount,
            'pending_count'  => $pendingCount ?? 0,
            'items'   => $items,
            'unit'    => [
                'id'              => (int) $unit['id'],
                'unit_number'     => $unit['unit_number'],
                'maintenance_fee' => (float) ($unit['maintenance_fee'] ?? 0),
            ],
            'condo' => [
                'name'            => $condo['name'] ?? '',
                'billing_due_day' => (int) ($condo['billing_due_day'] ?? 1),
                'bank_name'       => $condo['bank_name'] ?? '',
                'bank_clabe'      => $condo['bank_clabe'] ?? '',
                'bank_rfc'        => $condo['bank_rfc'] ?? '',
                'bank_card'       => !empty($condo['bank_card']) ? implode(' ', str_split(preg_replace('/\D/', '', $condo['bank_card']), 4)) : '',
            ],
        ]);
    }

    /**
     * GET /api/v1/resident/finances/community?month=X&year=Y
     * Devuelve resumen financiero comunitario: ingresos, egresos, categorías, movimientos.
     * Alimenta la pestaña "Comunidad" de la app.
     */
    public function communityFinances()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $condo = $condoModel->find($tenantId);

        $db = \Config\Database::connect();
        
        $isAdmin = false;
        $pivot = $db->table('user_condominium_roles ucr')
            ->select('roles.name as role_name')
            ->join('roles', 'roles.id = ucr.role_id', 'left')
            ->where('ucr.user_id', $userId)
            ->where('ucr.condominium_id', $tenantId)
            ->get()->getRowArray();
            
        if ($pivot) {
            $roleName = strtolower($pivot['role_name'] ?? '');
            if (in_array($roleName, ['admin', 'super_admin', 'owner', 'super admin', 'superadmin'])) {
                $isAdmin = true;
            }
        }

        if (!$isAdmin) {
            $residentModel = new ResidentModel();
            $resident = $residentModel->where('user_id', $userId)->first();
            if (!$resident) return $this->respondError('Residente no encontrado', 404);

            // Verificar acceso comunitario
            $type = $resident['type'] ?? 'tenant';
            $accessLevel = ($type === 'owner')
                ? ($condo['owner_financial_access'] ?? 'none')
                : ($condo['tenant_financial_access'] ?? 'none');

            if ($accessLevel !== 'unit_community') {
                return $this->respondError('No tienes acceso para consultar finanzas de la comunidad', 403);
            }
        }

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year')  ?: date('Y'));

        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate   = date('Y-m-t', strtotime($startDate));

        $db = \Config\Database::connect();

        // Total Ingresos (créditos = pagos registrados, c.type = 'income')
        $incomeRow = $db->table('financial_transactions ft')
            ->join('financial_categories c', 'c.id = ft.category_id', 'left')
            ->selectSum('ft.amount')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('c.type', 'income')
            ->where('ft.deleted_at IS NULL')
            ->where('ft.due_date >=', $startDate)
            ->where('ft.due_date <=', $endDate)
            ->get()->getRowArray();
        $totalIncomes = (float) ($incomeRow['amount'] ?? 0);
        log_message('error', "DEBUG COMMUNITY - tenant: $tenantId, month: $month, year: $year, totalIncomes: $totalIncomes");

        // Total Egresos (gastos / expenses)
        $expenseRow = $db->table('financial_transactions ft')
            ->join('financial_categories c', 'c.id = ft.category_id', 'left')
            ->selectSum('ft.amount')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('c.type', 'expense')
            ->where('ft.deleted_at IS NULL')
            ->where('ft.due_date >=', $startDate)
            ->where('ft.due_date <=', $endDate)
            ->get()->getRowArray();
        $totalExpenses = (float) ($expenseRow['amount'] ?? 0);

        // Tasa de cobranza: cargos pagados / cargos totales del periodo
        $totalChargesRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('condominium_id', $tenantId)
            ->where('type', 'charge')
            ->where('deleted_at IS NULL')
            ->where('due_date >=', $startDate)
            ->where('due_date <=', $endDate)
            ->get()->getRowArray();
        $totalCharges = (float) ($totalChargesRow['amount'] ?? 0);

        $paidChargesRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('condominium_id', $tenantId)
            ->where('type', 'charge')
            ->where('status', 'paid')
            ->where('deleted_at IS NULL')
            ->where('due_date >=', $startDate)
            ->where('due_date <=', $endDate)
            ->get()->getRowArray();
        $paidCharges = (float) ($paidChargesRow['amount'] ?? 0);

        $collectionRate = ($totalCharges > 0) ? round(($paidCharges / $totalCharges) * 100, 1) : 0;

        // Gastos por categoría (para gráfica de pie)
        $expensesByCategory = $db->table('financial_transactions ft')
            ->select('IFNULL(cats.name, "General") as category, SUM(ft.amount) as total')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('cats.type', 'expense')
            ->where('ft.deleted_at IS NULL')
            ->where('ft.due_date >=', $startDate)
            ->where('ft.due_date <=', $endDate)
            ->groupBy('cats.id, cats.name')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        // Calcular porcentaje por categoría
        foreach ($expensesByCategory as &$cat) {
            $cat['total'] = (float) $cat['total'];
            $cat['percentage'] = ($totalExpenses > 0)
                ? round(($cat['total'] / $totalExpenses) * 100, 1)
                : 0;
        }

        // Ingresos por categoría (para gráfica de pie)
        $incomesByCategory = $db->table('financial_transactions ft')
            ->select('IFNULL(cats.name, "General") as category, SUM(ft.amount) as total')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('cats.type', 'income')
            ->where('ft.deleted_at IS NULL')
            ->where('ft.due_date >=', $startDate)
            ->where('ft.due_date <=', $endDate)
            ->groupBy('cats.id, cats.name')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        foreach ($incomesByCategory as &$icat) {
            $icat['total'] = (float) $icat['total'];
            $icat['percentage'] = ($totalIncomes > 0)
                ? round(($icat['total'] / $totalIncomes) * 100, 1)
                : 0;
        }

        // Movimientos recientes (últimos 20 del periodo)
        $movements = $db->table('financial_transactions ft')
            ->select('ft.id, cats.type, ft.amount, ft.description, ft.due_date as date, ft.status')
            ->join('financial_categories cats', 'cats.id = ft.category_id', 'left')
            ->where('ft.condominium_id', $tenantId)
            ->where('ft.type', 'credit')
            ->whereIn('ft.status', ['paid', 'completed'])
            ->where('ft.deleted_at IS NULL')
            ->where('ft.due_date >=', $startDate)
            ->where('ft.due_date <=', $endDate)
            ->orderBy('ft.created_at', 'DESC')
            ->limit(20)
            ->get()->getResultArray();

        // Normalizar type para Flutter (income/expense)
        foreach ($movements as &$mov) {
            $mov['amount'] = (float) $mov['amount'];
            if ($mov['type'] === 'credit') $mov['type'] = 'income';
        }

        return $this->respondSuccess([
            'summary' => [
                'total_incomes'   => $totalIncomes,
                'total_expenses'  => $totalExpenses,
                'collection_rate' => $collectionRate,
                'net_income'      => $totalIncomes - $totalExpenses,
            ],
            'expenses_by_category' => $expensesByCategory,
            'incomes_by_category'  => $incomesByCategory,
            'movements'            => $movements,
        ]);
    }

    /**
     * GET /api/v1/finance/community-report
     * Genera un PDF de reporte comunitario
     */
    public function communityReport()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $condoModel = new CondominiumModel();
        $condo = $condoModel->find($tenantId);

        $db = \Config\Database::connect();
        $isAdmin = false;
        $pivot = $db->table('user_condominium_roles ucr')
            ->select('roles.name as role_name')
            ->join('roles', 'roles.id = ucr.role_id', 'left')
            ->where('ucr.user_id', $userId)
            ->where('ucr.condominium_id', $tenantId)
            ->get()->getRowArray();
            
        if ($pivot) {
            $roleName = strtolower($pivot['role_name'] ?? '');
            if (in_array($roleName, ['admin', 'super_admin', 'owner', 'super admin', 'superadmin'])) {
                $isAdmin = true;
            }
        }

        if (!$isAdmin) {
            $residentModel = new ResidentModel();
            $resident = $residentModel->where('user_id', $userId)->first();
            if (!$resident) return $this->respondError('Residente no encontrado', 404);
        }

        $type = $resident['type'] ?? 'tenant';
        $accessLevel = ($type === 'owner') ? ($condo['owner_financial_access'] ?? 'none') : ($condo['tenant_financial_access'] ?? 'none');

        if ($accessLevel !== 'unit_community') {
            return $this->respondError('No tienes acceso para consultar finanzas de la comunidad', 403);
        }

        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year') ?: date('Y'));

        // Simular el parametro GET que usa el Controlador Admin (YYYY-MM)
        $_GET['month'] = sprintf('%04d-%02d', $year, $month);

        // Delegar la renderización del reporte corporativo al controlador de Finanzas Admin
        $adminFinanceController = new \App\Controllers\Admin\FinanceController();
        $adminFinanceController->initController($this->request, $this->response, service('logger'));
        
        return $adminFinanceController->descargarReporteMensual();
    }

    /**
     * POST /api/v1/resident/finances/{transactionId}/receipt
     * Sube un comprobante de pago (imagen/PDF) para una transacción pendiente.
     */
    public function uploadReceipt($transactionId = null)
    {
        // CORS preflight
        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        // Verificar que la transacción existe y pertenece a una unidad del residente
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        $db = \Config\Database::connect();
        $transaction = $db->table('financial_transactions')
            ->where('id', $transactionId)
            ->where('unit_id', $resident['unit_id'])
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();

        if (!$transaction) {
            // Debug: ayuda a diagnosticar el caso
            log_message('error', "uploadReceipt: txn_id={$transactionId}, unit_id={$resident['unit_id']}, user_id={$userId}");
            return $this->respondError('Transacción no encontrada o no pertenece a tu unidad', 404);
        }

        $file = $this->request->getFile('receipt');
        if (!$file || !$file->isValid()) {
            return $this->respondError('Comprobante no proporcionado o inválido', 422);
        }

        // Validar tipo de archivo
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        if (!in_array($file->getClientMimeType(), $allowedMimes)) {
            return $this->respondError('Tipo de archivo no permitido. Use JPG, PNG o PDF.', 422);
        }

        // Validar tamaño (máx 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->respondError('El archivo excede el tamaño máximo de 10MB', 422);
        }

        $uploadPath = WRITEPATH . 'uploads/financial/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = 'receipt_' . $transactionId . '_' . time() . '.' . $file->getClientExtension();
        $file->move($uploadPath, $newName);

        // Guardar referencia en la transacción
        $db->table('financial_transactions')->where('id', $transactionId)->update([
            'attachment'  => $newName,
            'updated_at'  => date('Y-m-d H:i:s'),
        ]);

        // Registrar en tabla de pagos también
        $paymentModel = new PaymentModel();
        $paymentModel->insert([
            'condominium_id' => $tenantId,
            'unit_id'        => $resident['unit_id'],
            'transaction_id' => $transactionId,
            'amount'         => $transaction['amount'],
            'payment_method' => 'transfer',
            'reference_code' => '',
            'proof_url'      => 'financial/' . $newName,
            'notes'          => 'Comprobante subido desde la app',
            'status'         => 'pending',
        ]);

        // Notificar a todos los administradores del condominio
        $admins = $db->table('user_condominium_roles ucr')
            ->select('ucr.user_id as id')
            ->join('roles r', 'ucr.role_id = r.id')
            ->where('ucr.condominium_id', $tenantId)
            ->whereIn('r.name', ['admin', 'super_admin'])
            ->get()->getResultArray();
        $unit = $db->table('units')->where('id', $resident['unit_id'])->get()->getRowArray();
        $unitName = $unit ? $unit['unit_number'] : 'Desconocida';
        
        $userObj = $db->table('users')->where('id', $userId)->get()->getRowArray();
        $uploaderName = $userObj ? ($userObj['first_name'] . ' ' . $userObj['last_name']) : 'Un residente';
        
        foreach ($admins as $admin) {
            \App\Models\Tenant\NotificationModel::notify(
                $tenantId, 
                $admin['id'], 
                'payment_status', 
                'Comprobante Subido', 
                $uploaderName . ' subió un comprobante de pago para ' . $unitName,
                ['action_url' => 'admin/finanzas/pagos-por-unidad/' . $unit['hash_id']]
            );
        }

        return $this->respondSuccess([
            'message'  => 'Comprobante subido exitosamente. Pendiente de validación por la administración.',
            'filename' => $newName,
        ]);
    }

    /**
     * GET /api/v1/resident/finances/account-statement?month=X&year=Y
     * Genera y retorna el PDF del Estado de Cuenta de la unidad del residente autenticado.
     * Reutiliza la lógica del Admin FinanceController::downloadAccountStatement.
     */
    public function downloadMyStatement()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        $unitId = $resident['unit_id'];

        // Delegar al controlador admin (que ya tiene la lógica completa de generación de PDF)
        $adminFinanceController = new \App\Controllers\Admin\FinanceController();
        $adminFinanceController->initController($this->request, $this->response, service('logger'));

        // El admin controller usa el unit_id o hash_id como identifier
        return $adminFinanceController->downloadAccountStatement($unitId);
    }

    /**
     * GET /api/v1/resident/finances/payment-receipt/{transactionId}
     * Genera y retorna el PDF del Recibo de Pago de una transacción específica.
     */
    public function downloadMyReceipt($transactionId = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        // Verificar que la transacción pertenece a la unidad del residente
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();
        
        // Primero intentamos buscarlo como un ID de comprobante subido (tabla payments)
        $payment = $db->table('payments')
            ->where('id', $transactionId)
            ->where('unit_id', $resident['unit_id'])
            ->where('condominium_id', $tenantId)
            ->get()->getRowArray();
            
        $finTxId = null;
        if ($payment) {
            if (!empty($payment['transaction_id'])) {
                $finTxId = $payment['transaction_id'];
            } else {
                // Soporte para registros históricos sin transaction_id guardado
                $historicTx = $db->table('financial_transactions')
                    ->where('unit_id', $resident['unit_id'])
                    ->where('condominium_id', $tenantId)
                    ->where('type', 'credit')
                    ->where('amount', $payment['amount'])
                    ->orderBy('created_at', 'DESC')
                    ->get()->getRowArray();
                
                $finTxId = $historicTx ? $historicTx['id'] : $transactionId;
            }
        } else {
            $finTxId = $transactionId;
        }

        $transaction = $db->table('financial_transactions')
            ->where('id', $finTxId)
            ->where('unit_id', $resident['unit_id'])
            ->where('condominium_id', $tenantId)
            ->where('type', 'credit')
            ->get()->getRowArray();

        if (!$transaction) {
            return $this->respondError('Recibo no encontrado (si recién lo enviaste, la administración debe aplicarlo primero)', 404);
        }

        // Delegar al controlador admin
        $adminFinanceController = new \App\Controllers\Admin\FinanceController();
        $adminFinanceController->initController($this->request, $this->response, service('logger'));
        return $adminFinanceController->downloadPaymentReceipt((int) $finTxId);
    }
}


