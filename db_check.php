<?php
require 'public/index.php';
$db = \Config\Database::connect();
$query = $db->query("DESCRIBE polls");
print_r($query->getResultArray());
