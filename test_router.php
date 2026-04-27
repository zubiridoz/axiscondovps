<?php
define('FCPATH', __DIR__ . '/public/');
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';
\CodeIgniter\Boot::bootWeb($paths);
$routes = \Config\Services::routes();
print_r($routes->getRoutes()['admin/anuncios/archivo/(.*)'] ?? 'Not found');
