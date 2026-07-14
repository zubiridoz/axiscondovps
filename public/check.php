<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv(ROOTPATH))->load();
$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$unit = $db->table('units')->where('unit_number', 'A-100')->get()->getRowArray();
if (!$unit) die("Unit not found\n");
echo "Unit ID: {$unit['id']}\n";
echo "Initial Balance: {$unit['initial_balance']}\n";

$txs = $db->table('financial_transactions')
    ->where('unit_id', $unit['id'])
    ->orderBy('created_at', 'DESC')
    ->limit(10)
    ->get()->getResultArray();

foreach ($txs as $tx) {
    echo "ID: {$tx['id']} | Type: {$tx['type']} | Amount: {$tx['amount']} | Paid: {$tx['amount_paid']} | Status: {$tx['status']} | ExtFeeId: {$tx['extraordinary_fee_id']} | Desc: {$tx['description']}\n";
}
