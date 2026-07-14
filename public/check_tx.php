<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require 'vendor/autoload.php';
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require $pathsConfig;
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

$db = \Config\Database::connect();
$transactions = $db->table('financial_transactions')
    ->orderBy('id', 'DESC')
    ->limit(10)
    ->get()->getResultArray();

print_r($transactions);
