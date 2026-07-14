<?php
require '../vendor/autoload.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$db = \Config\Database::connect();
$totalPaid = $db->table('financial_transactions')
    ->selectSum('amount')
    ->where('extraordinary_fee_id', 3)
    ->where('unit_id', 419)
    ->where('type', 'credit')
    ->get()->getRow();
echo json_encode($totalPaid);
