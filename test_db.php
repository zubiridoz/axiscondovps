<?php
define('ENVIRONMENT', 'development');
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';
$db = \Config\Database::connect();
$bookings = $db->table('bookings')->where('short_hash', '1f6f45')->get()->getResultArray();
foreach ($bookings as $b) {
    print_r($b);
    $id = $b['id'];
    $txs = $db->table('financial_transactions')->where('source', 'booking_'.$id)->get()->getResultArray();
    print_r($txs);
}
