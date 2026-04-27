<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Services\TenantService;
use App\Models\Tenant\UnitModel;

class AdminFinanceApiController extends ResourceController
{
    public function getUnits()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) {
            return $this->failUnauthorized('No hay condominio activo');
        }

        $db = \Config\Database::connect();
        $units = $db->table('units u')
            ->select('u.id, u.unit_number')
            ->select('CONCAT(us.first_name, " ", us.last_name) as resident_name')
            ->join('residents r', 'r.unit_id = u.id AND r.condominium_id = u.condominium_id', 'left')
            ->join('users us', 'us.id = r.user_id', 'left')
            ->where('u.condominium_id', $tenantId)
            ->orderBy('u.unit_number', 'ASC')
            ->get()
            ->getResultArray();

        return $this->respond([
            'status' => 'success',
            'data' => $units
        ]);
    }

    public function getUnitFinance($unitId)
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) {
            return $this->failUnauthorized('No hay condominio activo');
        }

        $db = \Config\Database::connect();
        
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $condo = $condoModel->find($tenantId);

        $unitModel = new UnitModel();
        $unit = $unitModel->find($unitId);

        log_message('error', 'DEBUG FINANCES - tenantId: ' . $tenantId);
        log_message('error', 'DEBUG FINANCES - requested unitId: ' . $unitId);
        log_message('error', 'DEBUG FINANCES - unit found: ' . ($unit ? json_encode($unit) : 'false'));

        if (!$unit || $unit['condominium_id'] != $tenantId) {
            return $this->respond([
                'status' => 'success',
                'data' => ['balance' => 0, 'items' => [], 'unit' => null]
            ]);
        }

        // Parámetros opcionales de mes/año
        $month = (int) ($this->request->getGet('month') ?: date('n'));
        $year  = (int) ($this->request->getGet('year')  ?: date('Y'));

        // Calcular balance real
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

        log_message('error', "DEBUG FINANCES - initialBalance: $initialBalance | totalCharges: $totalCharges | totalCredits: $totalCredits");

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

        $today = date('Y-m-d');
        $rawBalance = $initialBalance + $totalCharges - $totalCredits;

        if ($rawBalance < -0.01) {
            $accountStatus = 'a_favor';
            $overdueCount = 0;
        } else {
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

        return $this->respond([
            'status' => 'success',
            'data' => [
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
            ]
        ]);
    }

    /**
     * Reuses the storeRegistro logic from the web controller.
     */
    public function store()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) {
            return $this->failUnauthorized('No hay condominio activo');
        }

        try {
            // Reutilizar la lógica completa de storeRegistro() del controlador web
            $webController = new \App\Controllers\Admin\FinanceController();
            $webController->initController($this->request, $this->response, \Config\Services::logger());

            // storeRegistro utiliza $this->request->getPost() y valida isAJAX().
            // Flutter debe enviar la data como FormData (application/x-www-form-urlencoded o multipart/form-data)
            // y el header X-Requested-With: XMLHttpRequest.
            
            return $webController->storeRegistro();
        } catch (\Exception $e) {
            log_message('error', 'Error in store (API Admin): ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->failServerError('Error interno al registrar el movimiento: ' . $e->getMessage());
        }
    }

    public function communityFinances()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->failUnauthorized("No hay condominio activo");

        $month = (int) ($this->request->getGet("month") ?: date("n"));
        $year  = (int) ($this->request->getGet("year")  ?: date("Y"));

        $startDate = sprintf("%04d-%02d-01", $year, $month);
        $endDate   = date("Y-m-t", strtotime($startDate));

        $db = \Config\Database::connect();

        $incomeRow = $db->table("financial_transactions ft")
            ->join("financial_categories c", "c.id = ft.category_id", "left")
            ->selectSum("ft.amount")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("c.type", "income")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->get()->getRowArray();
        $totalIncomes = (float) ($incomeRow["amount"] ?? 0);

        $expenseRow = $db->table("financial_transactions ft")
            ->join("financial_categories c", "c.id = ft.category_id", "left")
            ->selectSum("ft.amount")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("c.type", "expense")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->get()->getRowArray();
        $totalExpenses = (float) ($expenseRow["amount"] ?? 0);

        $netBalance = $totalIncomes - $totalExpenses;

        $chargeRow = $db->table("financial_transactions")
            ->selectSum("amount")
            ->where("condominium_id", $tenantId)
            ->where("type", "charge")
            ->where("status !=", "cancelled")
            ->where("deleted_at IS NULL")
            ->where("due_date >=", $startDate)
            ->where("due_date <=", $endDate)
            ->get()->getRowArray();
        $totalExpected = (float) ($chargeRow["amount"] ?? 0);

        $paidRow = $db->table("financial_transactions")
            ->selectSum("amount_paid")
            ->where("condominium_id", $tenantId)
            ->where("type", "charge")
            ->where("status !=", "cancelled")
            ->where("deleted_at IS NULL")
            ->where("due_date >=", $startDate)
            ->where("due_date <=", $endDate)
            ->get()->getRowArray();
        $totalCollected = (float) ($paidRow["amount_paid"] ?? 0);

        $collectionRate = ($totalExpected > 0) ? round(($totalCollected / $totalExpected) * 100, 1) : 0;

        $incomeDistribution = $db->table("financial_transactions ft")
            ->select("c.name, SUM(ft.amount) as total")
            ->join("financial_categories c", "c.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("c.type", "income")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->groupBy("c.id, c.name")
            ->orderBy("total", "DESC")
            ->get()->getResultArray();

        $expenseDistribution = $db->table("financial_transactions ft")
            ->select("c.name, SUM(ft.amount) as total")
            ->join("financial_categories c", "c.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("c.type", "expense")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->groupBy("c.id, c.name")
            ->orderBy("total", "DESC")
            ->get()->getResultArray();

        foreach (["incomeDistribution" => &$incomeDistribution, "expenseDistribution" => &$expenseDistribution] as $key => &$dist) {
            $total = ($key === "incomeDistribution") ? $totalIncomes : $totalExpenses;
            foreach ($dist as &$item) {
                $item["total"] = (float) $item["total"];
                $item["percentage"] = ($total > 0) ? round(($item["total"] / $total) * 100, 1) : 0;
            }
        }

        $recentTransactions = $db->table("financial_transactions ft")
            ->select("ft.id, c.type as category_type, ft.amount, ft.description, ft.due_date as date, ft.status")
            ->join("financial_categories c", "c.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->orderBy("ft.created_at", "DESC")
            ->limit(10)
            ->get()->getResultArray();

        foreach ($recentTransactions as &$mov) {
            $mov["amount"] = (float) $mov["amount"];
            $mov["type"] = ($mov["category_type"] === "expense") ? "expense" : "income";
        }

        return $this->respond([
            "status" => "success",
            "data" => [
                "summary" => [
                    "total_incomes"   => $totalIncomes,
                    "total_expenses"  => $totalExpenses,
                    "net_balance"     => $netBalance,
                    "collection_rate" => $collectionRate
                ],
                "incomes_by_category"  => $incomeDistribution,
                "expenses_by_category" => $expenseDistribution,
                "recent_transactions"  => $recentTransactions
            ]
        ]);
    }

    public function communityTransactions()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->failUnauthorized("No hay condominio activo");

        $month = (int) ($this->request->getGet("month") ?: date("n"));
        $year  = (int) ($this->request->getGet("year")  ?: date("Y"));

        $startDate = sprintf("%04d-%02d-01", $year, $month);
        $endDate   = date("Y-m-t", strtotime($startDate));

        $db = \Config\Database::connect();

        $incomeRow = $db->table("financial_transactions ft")
            ->join("financial_categories cats", "cats.id = ft.category_id", "left")
            ->selectSum("ft.amount")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("cats.type", "income")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->get()->getRowArray();
        $totalIncomes = (float) ($incomeRow["amount"] ?? 0);

        $expenseRow = $db->table("financial_transactions ft")
            ->join("financial_categories cats", "cats.id = ft.category_id", "left")
            ->selectSum("ft.amount")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("cats.type", "expense")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->get()->getRowArray();
        $totalExpenses = (float) ($expenseRow["amount"] ?? 0);

        $chargeRow = $db->table("financial_transactions")
            ->selectSum("amount")
            ->where("condominium_id", $tenantId)
            ->where("type", "charge")
            ->where("status !=", "cancelled")
            ->where("deleted_at IS NULL")
            ->where("due_date >=", $startDate)
            ->where("due_date <=", $endDate)
            ->get()->getRowArray();
        $totalExpected = (float) ($chargeRow["amount"] ?? 0);

        $paidRow = $db->table("financial_transactions")
            ->selectSum("amount_paid")
            ->where("condominium_id", $tenantId)
            ->where("type", "charge")
            ->where("status !=", "cancelled")
            ->where("deleted_at IS NULL")
            ->where("due_date >=", $startDate)
            ->where("due_date <=", $endDate)
            ->get()->getRowArray();
        $totalCollected = (float) ($paidRow["amount_paid"] ?? 0);

        $collectionRate = ($totalExpected > 0) ? round(($totalCollected / $totalExpected) * 100, 1) : 0;

        $expensesByCategory = $db->table("financial_transactions ft")
            ->select("cats.name as category, SUM(ft.amount) as total")
            ->join("financial_categories cats", "cats.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("cats.type", "expense")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->groupBy("cats.id, cats.name")
            ->orderBy("total", "DESC")
            ->get()->getResultArray();

        foreach ($expensesByCategory as &$cat) {
            $cat["total"] = (float) $cat["total"];
            $cat["percentage"] = ($totalExpenses > 0) ? round(($cat["total"] / $totalExpenses) * 100, 1) : 0;
        }

        $incomesByCategory = $db->table("financial_transactions ft")
            ->select("cats.name as category, SUM(ft.amount) as total")
            ->join("financial_categories cats", "cats.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("cats.type", "income")
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->groupBy("cats.id, cats.name")
            ->orderBy("total", "DESC")
            ->get()->getResultArray();

        foreach ($incomesByCategory as &$icat) {
            $icat["total"] = (float) $icat["total"];
            $icat["percentage"] = ($totalIncomes > 0) ? round(($icat["total"] / $totalIncomes) * 100, 1) : 0;
        }

        $movements = $db->table("financial_transactions ft")
            ->select("ft.id, cats.type, ft.amount, ft.description, ft.due_date as date, ft.status")
            ->join("financial_categories cats", "cats.id = ft.category_id", "left")
            ->where("ft.condominium_id", $tenantId)
            ->where("ft.type", "credit")
            ->whereIn("ft.status", ["paid", "completed"])
            ->where("ft.deleted_at IS NULL")
            ->where("ft.due_date >=", $startDate)
            ->where("ft.due_date <=", $endDate)
            ->orderBy("ft.created_at", "DESC")
            ->limit(20)
            ->get()->getResultArray();

        foreach ($movements as &$mov) {
            $mov["amount"] = (float) $mov["amount"];
            if ($mov["type"] === "credit") $mov["type"] = "income";
        }

        return $this->respond([
            "status" => "success",
            "data" => [
                "summary" => [
                    "total_incomes"   => $totalIncomes,
                    "total_expenses"  => $totalExpenses,
                    "collection_rate" => $collectionRate,
                    "net_income"      => $totalIncomes - $totalExpenses,
                ],
                "expenses_by_category" => $expensesByCategory,
                "incomes_by_category"  => $incomesByCategory,
                "movements"            => $movements,
            ]
        ]);
    }

    public function communityReport()
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->failUnauthorized("No hay condominio activo");

        $month = (int) ($this->request->getGet("month") ?: date("n"));
        $year  = (int) ($this->request->getGet("year") ?: date("Y"));
        $_GET["month"] = sprintf("%04d-%02d", $year, $month);

        $adminFinanceController = new \App\Controllers\Admin\FinanceController();
        $adminFinanceController->initController($this->request, $this->response, service("logger"));
        
        $pdfContent = $adminFinanceController->descargarReporteMensual(true);
        
        return $this->response
            ->setHeader("Content-Type", "application/pdf")
            ->setHeader("Content-Disposition", "attachment; filename=\"reporte.pdf\"")
            ->setBody($pdfContent);
    }

    public function unitStatement($unitId)
    {
        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->failUnauthorized("No hay condominio activo");

        // Verify the unit belongs to the tenant
        $db = \Config\Database::connect();
        $unit = $db->table("units")->where("id", $unitId)->where("condominium_id", $tenantId)->get()->getRowArray();
        if (!$unit) return $this->failNotFound("Unidad no encontrada");

        $adminFinanceController = new \App\Controllers\Admin\FinanceController();
        $adminFinanceController->initController($this->request, $this->response, service("logger"));
        
        $pdfContent = $adminFinanceController->downloadAccountStatement($unitId, true);
        
        return $this->response
            ->setHeader("Content-Type", "application/pdf")
            ->setHeader("Content-Disposition", "attachment; filename=\"estado_cuenta.pdf\"")
            ->setBody($pdfContent);
    }

    public function uploadReceipt($transactionId = null)
    {
        if ($this->request->getMethod() === "options") {
            return $this->response->setStatusCode(200);
        }

        $tenantId = TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->failUnauthorized("No hay condominio activo");

        $db = \Config\Database::connect();
        $transaction = $db->table("financial_transactions")
            ->where("id", $transactionId)
            ->where("condominium_id", $tenantId)
            ->get()->getRowArray();

        if (!$transaction) {
            return $this->failNotFound("Transacción no encontrada");
        }

        $file = $this->request->getFile("receipt");
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors("Comprobante no proporcionado o inválido");
        }

        $allowedMimes = ["image/jpeg", "image/png", "image/webp", "application/pdf"];
        if (!in_array($file->getClientMimeType(), $allowedMimes)) {
            return $this->failValidationErrors("Tipo de archivo no permitido. Use JPG, PNG o PDF.");
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return $this->failValidationErrors("El archivo excede el tamaño máximo de 10MB");
        }

        $uploadPath = WRITEPATH . "uploads/financial/";
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = "receipt_" . $transactionId . "_" . time() . "." . $file->getClientExtension();
        $file->move($uploadPath, $newName);

        $db->table("financial_transactions")->where("id", $transactionId)->update([
            "attachment"  => $newName,
            "status"      => "pending", // Reset status to pending validation
            "updated_at"  => date("Y-m-d H:i:s"),
        ]);

        return $this->respond([
            "status" => "success",
            "message"  => "Comprobante subido exitosamente. Pendiente de validación.",
            "filename" => $newName,
        ]);
    }
}
