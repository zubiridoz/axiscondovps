<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class TestDB extends Controller {
    public function index() {
        $db = \Config\Database::connect();
        $bookings = $db->table('bookings')->get()->getResultArray();
        echo "Total bookings: " . count($bookings) . "\n";
        if (count($bookings) > 0) {
            print_r($bookings[0]);
        }
    }
}
