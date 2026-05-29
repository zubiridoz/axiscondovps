<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
chdir(__DIR__);
$pathsConfig = FCPATH . '../app/Config/Paths.php';
require $pathsConfig;
$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Ensure we have a DB connection
$db = \Config\Database::connect();
$db->transStart();

// 1. Setup Test Data
$tenantId = 1;
$unitId = 1;
$userId = 1;

// Create Amenity
$db->table('amenities')->insert([
    'condominium_id' => $tenantId,
    'name' => 'Test Amenity',
    'has_cost' => 1,
    'price' => 500.00,
    'is_reservable' => 1,
    'is_active' => 1,
]);
$amenityId = $db->insertID();

echo "Setup complete. Amenity ID: $amenityId\n";

// --- Caso 1: Doble aprobación ---
echo "\n--- Caso 1: Doble aprobación ---\n";
// Create Pending Booking
$db->table('bookings')->insert([
    'condominium_id' => $tenantId,
    'amenity_id' => $amenityId,
    'unit_id' => $unitId,
    'user_id' => $userId,
    'start_time' => date('Y-m-d H:i:s'),
    'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
    'status' => 'pending',
    'booking_price' => 500.00,
]);
$bookingId1 = $db->insertID();

// Simulate Approve 1
$db->table('bookings')->update(['status' => 'approved'], ['id' => $bookingId1]);
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId1);

// Simulate Approve 2
$db->table('bookings')->update(['status' => 'approved'], ['id' => $bookingId1]);
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId1);

$charges = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId1)->get()->getResultArray();
echo "Caso 1 - Expected 1 charge: Found " . count($charges) . "\n";
if (count($charges) == 1) {
    echo "Caso 1: PASSED\n";
} else {
    echo "Caso 1: FAILED\n";
}

// --- Caso 2: Cancelar y reaprobar ---
echo "\n--- Caso 2: Cancelar y reaprobar ---\n";
$db->table('bookings')->insert([
    'condominium_id' => $tenantId,
    'amenity_id' => $amenityId,
    'unit_id' => $unitId,
    'user_id' => $userId,
    'start_time' => date('Y-m-d H:i:s'),
    'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
    'status' => 'pending',
    'booking_price' => 500.00,
]);
$bookingId2 = $db->insertID();

// Approve
$db->table('bookings')->update(['status' => 'approved'], ['id' => $bookingId2]);
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId2);
$initialCharge = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId2)->get()->getRowArray();
$initialChargeId = $initialCharge['id'];

// Cancel
$db->table('bookings')->update(['status' => 'cancelled'], ['id' => $bookingId2]);
\App\Models\Tenant\FinancialTransactionModel::removeBookingCharge($bookingId2);

$deletedCharge = $db->table('financial_transactions')->where('id', $initialChargeId)->get()->getRowArray();
echo "Deleted charge deleted_at (should not be empty): " . ($deletedCharge['deleted_at'] ?? 'EMPTY') . "\n";

// Re-approve
$db->table('bookings')->update(['status' => 'approved'], ['id' => $bookingId2]);
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId2);

$restoredCharge = $db->table('financial_transactions')->where('id', $initialChargeId)->get()->getRowArray();
$allCharges = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId2)->get()->getResultArray();
echo "Caso 2 - Expected ID $initialChargeId restored. Found " . count($allCharges) . " charge(s). deleted_at: " . ($restoredCharge['deleted_at'] ?? 'NULL') . "\n";
if (count($allCharges) == 1 && empty($restoredCharge['deleted_at'])) {
    echo "Caso 2: PASSED\n";
} else {
    echo "Caso 2: FAILED\n";
}

// --- Caso 3: Cambio de precio posterior ---
echo "\n--- Caso 3: Cambio de precio posterior ---\n";
$db->table('bookings')->insert([
    'condominium_id' => $tenantId,
    'amenity_id' => $amenityId,
    'unit_id' => $unitId,
    'user_id' => $userId,
    'start_time' => date('Y-m-d H:i:s'),
    'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
    'status' => 'pending',
    'booking_price' => 500.00, // Snapshot price
]);
$bookingId3 = $db->insertID();

// Change amenity price
$db->table('amenities')->update(['price' => 700.00], ['id' => $amenityId]);

// Approve
$db->table('bookings')->update(['status' => 'approved'], ['id' => $bookingId3]);
\App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId3);

$charge3 = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId3)->get()->getRowArray();
echo "Caso 3 - Expected Amount 500.00. Found: " . $charge3['amount'] . "\n";
if ((float)$charge3['amount'] == 500.00) {
    echo "Caso 3: PASSED\n";
} else {
    echo "Caso 3: FAILED\n";
}

// Rollback everything
$db->transRollback();
echo "\nTests completed. Rollback executed.\n";
