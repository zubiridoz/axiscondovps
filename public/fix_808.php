<?php
require 'index.php'; // Boot the app

$db = \Config\Database::connect();
// Fix ID 808
$db->table('financial_transactions')->where('id', 808)->update([
    'amount_paid' => 0.00,
    'status' => 'pending'
]);

// Run sync
$controller = new \App\Controllers\Admin\FinanceController();
$reflection = new \ReflectionMethod($controller, 'syncUnitFinancialState');
$reflection->setAccessible(true);
$reflection->invoke($controller, 296);

echo "Fixed 808 and synced A-100!";
?>
