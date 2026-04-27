<?php
define('FCPATH', __DIR__ . '/public/');
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';
\CodeIgniter\Boot::bootWeb($paths);
$router = \Config\Services::router();
$routes = \Config\Services::routes();

$request = \Config\Services::request();
$router->handle('admin/anuncios/archivo/ann_69eee8ac12e39_1777264812.jpg');
echo "Controller: " . $router->controllerName() . "\n";
echo "Method: " . $router->methodName() . "\n";
print_r($router->params());
