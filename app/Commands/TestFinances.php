<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestFinances extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:finances';
    protected $description = 'Runs the 3 financial tests for bookings.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Setup Test Data
        $tenantId = $db->table('condominiums')->select('id')->limit(1)->get()->getRowArray()['id'] ?? null;
        $unitId = $db->table('units')->select('id')->where('condominium_id', $tenantId)->limit(1)->get()->getRowArray()['id'] ?? null;
        $userId = $db->table('users')->select('id')->limit(1)->get()->getRowArray()['id'] ?? null;

        if (!$tenantId || !$unitId || !$userId) {
            CLI::write("Could not find required test data (tenant, unit, or user).", 'red');
            return;
        }

        // Bypass TenantService for CLI Tests
        \App\Services\TenantService::getInstance()->setTenantId($tenantId);

        // Create Amenity
        $amenityModel = new \App\Models\Tenant\AmenityModel();
        $amenityId = $amenityModel->insert([
            'condominium_id' => $tenantId,
            'name' => 'Test Amenity',
            'has_cost' => 1,
            'price' => 500.00,
            'is_reservable' => 1,
            'is_active' => 1,
            'hash_id' => uniqid(),
            'capacity' => 10,
        ], true);
        
        if (!$amenityId) {
            CLI::write("Amenity Insert Failed: " . print_r($amenityModel->errors(), true), 'red');
            return;
        }

        CLI::write("Setup complete. Amenity ID: $amenityId");

        $bookingModel = new \App\Models\Tenant\BookingModel();

        // --- Caso 1: Doble aprobación ---
        CLI::write("\n--- Caso 1: Doble aprobación ---", 'yellow');
        $bookingId1 = $bookingModel->insert([
            'condominium_id' => $tenantId,
            'amenity_id' => $amenityId,
            'unit_id' => $unitId,
            'user_id' => $userId,
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'status' => 'pending',
            'booking_price' => 500.00,
        ], true);

        if (!$bookingId1) {
            CLI::write("Booking 1 Insert Failed: " . print_r($bookingModel->errors(), true), 'red');
            return;
        }

        // Simulate Approve 1
        $bookingModel->update($bookingId1, ['status' => 'approved']);
        \App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId1);

        // Simulate Approve 2
        $bookingModel->update($bookingId1, ['status' => 'approved']);
        \App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId1);

        $charges = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId1)->where('type', 'charge')->get()->getResultArray();
        CLI::write("Caso 1 - Expected 1 charge: Found " . count($charges));
        if (count($charges) == 1) {
            CLI::write("Caso 1: PASSED", 'green');
        } else {
            CLI::write("Caso 1: FAILED", 'red');
        }

        // --- Caso 2: Cancelar y reaprobar ---
        CLI::write("\n--- Caso 2: Cancelar y reaprobar ---", 'yellow');
        $bookingId2 = $bookingModel->insert([
            'condominium_id' => $tenantId,
            'amenity_id' => $amenityId,
            'unit_id' => $unitId,
            'user_id' => $userId,
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'status' => 'pending',
            'booking_price' => 500.00,
        ], true);

        if (!$bookingId2) {
             CLI::write("Booking 2 Insert Failed: " . print_r($bookingModel->errors(), true), 'red');
             return;
        }

        // Approve
        $bookingModel->update($bookingId2, ['status' => 'approved']);
        \App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId2);
        $initialCharge = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId2)->get()->getRowArray();
        if (!$initialCharge) {
            CLI::write("Caso 2: FAILED to create initial charge", 'red');
        } else {
            $initialChargeId = $initialCharge['id'];

            // Cancel
            $bookingModel->update($bookingId2, ['status' => 'cancelled']);
            \App\Models\Tenant\FinancialTransactionModel::removeBookingCharge($bookingId2);

            $deletedCharge = $db->table('financial_transactions')->where('id', $initialChargeId)->get()->getRowArray();
            CLI::write("Deleted charge deleted_at (should not be empty): " . ($deletedCharge['deleted_at'] ?? 'EMPTY'));

            // Re-approve
            $bookingModel->update($bookingId2, ['status' => 'approved']);
            \App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId2);

            $restoredCharge = $db->table('financial_transactions')->where('id', $initialChargeId)->get()->getRowArray();
            $allCharges = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId2)->get()->getResultArray();
            CLI::write("Caso 2 - Expected ID $initialChargeId restored. Found " . count($allCharges) . " charge(s). deleted_at: " . ($restoredCharge['deleted_at'] ?? 'NULL'));
            if (count($allCharges) == 1 && empty($restoredCharge['deleted_at'])) {
                CLI::write("Caso 2: PASSED", 'green');
            } else {
                CLI::write("Caso 2: FAILED", 'red');
            }
        }

        // --- Caso 3: Cambio de precio posterior ---
        CLI::write("\n--- Caso 3: Cambio de precio posterior ---", 'yellow');
        $bookingId3 = $bookingModel->insert([
            'condominium_id' => $tenantId,
            'amenity_id' => $amenityId,
            'unit_id' => $unitId,
            'user_id' => $userId,
            'start_time' => date('Y-m-d H:i:s'),
            'end_time' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'status' => 'pending',
            'booking_price' => 500.00, // Snapshot price
        ], true);

        // Change amenity price
        $amenityModel->update($amenityId, ['price' => 700.00]);

        // Approve
        $bookingModel->update($bookingId3, ['status' => 'approved']);
        \App\Models\Tenant\FinancialTransactionModel::generateBookingCharge($bookingId3);

        $charge3 = $db->table('financial_transactions')->where('source', 'booking_'.$bookingId3)->get()->getRowArray();
        CLI::write("Caso 3 - Expected Amount 500.00. Found: " . $charge3['amount']);
        if ((float)$charge3['amount'] == 500.00) {
            CLI::write("Caso 3: PASSED", 'green');
        } else {
            CLI::write("Caso 3: FAILED", 'red');
        }

        // Rollback everything
        $db->transRollback();
        CLI::write("\nTests completed. Rollback executed.", 'green');
    }
}
