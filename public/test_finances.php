<?php
require '../vendor/autoload.php';
$app = \Config\Services::codeigniter(new \Config\App());
$app->initialize();

// Mock Session
$_SESSION['tenant_id'] = 2; // Quiero Casa

$controller = new \App\Controllers\Admin\FinanceController();
try {
    $result = $controller->dashboard();
    echo "Dashboard OK. ";
} catch (Exception $e) {
    echo "Dashboard Error: " . $e->getMessage();
}
try {
    $result2 = $controller->movimientos();
    echo "Movimientos OK. ";
} catch (Exception $e) {
    echo "Movimientos Error: " . $e->getMessage();
}
?>
