<?php
define('FCPATH', __DIR__ . '/public/');
require FCPATH . '../vendor/autoload.php';
$app = require FCPATH . '../system/bootstrap.php';
$app->initialize();

$db = \Config\Database::connect();
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge(95);
echo "Done\n";
