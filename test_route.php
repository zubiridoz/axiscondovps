<?php
define('FCPATH', __DIR__ . '/public/');
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'Boot.php';
\CodeIgniter\Boot::bootWeb($paths);
$router = \Config\Services::router();
$routes = \Config\Services::routes();
echo $routes->getRoutes()['admin/anuncios/archivo/(.*)'];
