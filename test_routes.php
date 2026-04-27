<?php
require 'vendor/autoload.php';
$routes = \Config\Services::routes();
print_r($routes->getPlaceholders());
