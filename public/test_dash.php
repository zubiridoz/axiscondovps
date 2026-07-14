<?php
require '../vendor/autoload.php';
$app = \Config\Services::codeigniter(new \Config\App());
$app->initialize();

// Mock TenantService
$_SESSION['tenant_id'] = 2; // Quiero Casa

$controller = new \App\Controllers\Admin\FinanceController();
$db = \Config\Database::connect();
$monthStart = '2026-07-01';
$monthEnd = '2026-07-31';
$condoId = 2;

$row = $db->query("
    SELECT IFNULL(SUM(ft.amount),0) AS total
    FROM financial_transactions ft
    INNER JOIN financial_categories c ON c.id = ft.category_id
    WHERE ft.condominium_id = ? AND ft.type = 'credit'
      AND ft.status = 'paid' AND c.type = 'income'
      AND ft.due_date BETWEEN ? AND ?
", [$condoId, $monthStart, $monthEnd])->getRow();

echo "Ingresos: " . ($row ? (float) $row->total : 0.00) . "\n";
?>
