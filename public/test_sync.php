<?php
require '../vendor/autoload.php';
// Boot framework
$app = \Config\Services::codeigniter(new \Config\App());
$app->initialize();
$db = \Config\Database::connect();
$unitId = 296;

$creditRow = $db->table('financial_transactions')
    ->select('SUM(amount) as total_credits')
    ->where('unit_id', $unitId)
    ->where('type', 'credit')
    ->where('status', 'paid')
    ->where('deleted_at IS NULL')
    ->get()->getRowArray();
$totalCredits = (float) ($creditRow['total_credits'] ?? 0);

$chargeAllocatedRow = $db->table('financial_transactions')
    ->select('SUM(CASE WHEN status IN ("paid", "completed") THEN amount ELSE amount_paid END) as total_allocated')
    ->where('unit_id', $unitId)
    ->where('type', 'charge')
    ->where('status !=', 'cancelled')
    ->where('deleted_at IS NULL')
    ->get()->getRowArray();
$totalAllocated = (float) ($chargeAllocatedRow['total_allocated'] ?? 0);

$floatingCredit = $totalCredits - $totalAllocated;

echo "Total Credits: " . $totalCredits . "\n";
echo "Total Allocated: " . $totalAllocated . "\n";
echo "Floating Credit: " . $floatingCredit . "\n";

?>
