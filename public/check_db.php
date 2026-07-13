<?php
require 'vendor/autoload.php';
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require $pathsConfig;
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

$db = \Config\Database::connect();
$fields = $db->getFieldNames('condominiums');
echo "Columns in condominiums table:\n";
print_r($fields);
