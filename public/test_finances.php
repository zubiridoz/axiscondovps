<?php
// Bootstrap CI4
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();
// CONDOMINIO ACALLI unit 308
$condoRow = $db->query("SELECT id FROM condominiums WHERE name LIKE '%ACALLI%'")->getRowArray();
if (!$condoRow) die("Condo not found\n");
$condoId = $condoRow['id'];

$unitRow = $db->query("SELECT id, initial_balance FROM units WHERE condominium_id = ? AND unit_number = '308'", [$condoId])->getRowArray();
if (!$unitRow) die("Unit not found\n");
$unitId = $unitRow['id'];

$initialBalance = (float) ($unitRow['initial_balance'] ?? 0);
$today = date('Y-m-d');

$chargesRow = $db->table('financial_transactions')->selectSum('amount')->where('unit_id', $unitId)->where('type', 'charge')->where('status !=', 'cancelled')->where('deleted_at IS NULL')->get()->getRowArray();
$totalCharges = (float) ($chargesRow['amount'] ?? 0);

$creditsRow = $db->table('financial_transactions')->selectSum('amount')->where('unit_id', $unitId)->where('type', 'credit')->where('status !=', 'cancelled')->where('deleted_at IS NULL')->get()->getRowArray();
$totalCredits = (float) ($creditsRow['amount'] ?? 0);

$overdueChargesRow = $db->table('financial_transactions')->selectSum('amount')->where('unit_id', $unitId)->where('type', 'charge')->where('status !=', 'cancelled')->where('due_date <', $today)->where('deleted_at IS NULL')->get()->getRowArray();
$totalOverdueCharges = (float) ($overdueChargesRow['amount'] ?? 0);

$debtVencida = $initialBalance + $totalOverdueCharges - $totalCredits;
$rawBalance = $initialBalance + $totalCharges - $totalCredits;

echo "Initial Balance: $initialBalance\n";
echo "Total Charges: $totalCharges\n";
echo "Total Credits: $totalCredits\n";
echo "Total Overdue Charges: $totalOverdueCharges\n";
echo "Debt Vencida: $debtVencida\n";
echo "Raw Balance: $rawBalance\n";

if ($debtVencida > 0.01) {
    $accountStatus = 'moroso';
} elseif ($rawBalance > 0.01) {
    $accountStatus = 'al_corriente';
} elseif ($rawBalance < -0.01) {
    $accountStatus = 'a_favor';
} else {
    $accountStatus = 'sin_adeudos';
}
echo "Account Status: $accountStatus\n";

