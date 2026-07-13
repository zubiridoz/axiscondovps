<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

// Bootstrap Spark environment (requires environment constants but does not exit immediately if we don't return from spark runner, wait, bootSpark sets up autoloader etc)
// Actually, let's load bootWeb or CLI services.
// To just load database connection, we only need autoloader and bootstrap. Let's look at system/Test/bootstrap.php or system/bootstrap.php.
// Let's do it cleanly:
require_once $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();

$condo = $db->table('condominiums')->first();
$unit = $db->table('units')->where('condominium_id', $condo['id'])->first();
$category = $db->table('financial_categories')->where('condominium_id', $condo['id'])->first();

if (!$condo || !$unit) {
    echo "Falta inicializar la base de datos con los seeders.\n";
    exit(1);
}

$transactionData = [
    'condominium_id' => $condo['id'],
    'unit_id' => $unit['id'],
    'category_id' => $category ? $category['id'] : 1,
    'type' => 'credit',
    'amount' => 1700.00,
    'description' => 'PAGO - COMPROBANTE APROBADO',
    'due_date' => date('Y-m-d'),
    'status' => 'paid',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s'),
];

$db->table('financial_transactions')->insert($transactionData);
$insertId = $db->insertID();

echo "Transaccion de prueba creada con ID: " . $insertId . "\n";
