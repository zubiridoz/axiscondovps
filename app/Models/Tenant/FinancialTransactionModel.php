<?php

namespace App\Models\Tenant;

class FinancialTransactionModel extends BaseTenantModel
{
    protected $table            = 'financial_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'category_id',
        'extraordinary_fee_id',
        'type',
        'amount',
        'description',
        'due_date',
        'status',
        'late_fee_applied',
        'attachment',
        'payment_method',
        'source'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Genera o restaura un cargo financiero por una reserva de amenidad.
     */
    public static function generateBookingCharge(int $bookingId): void
    {
        $db = \Config\Database::connect();
        $bookingModel = new BookingModel();
        $booking = $bookingModel->find($bookingId);

        if (!$booking) return;

        // Blindaje de Estado
        if ($booking['status'] !== 'approved') return;

        // Blindaje Financiero
        $price = (float) ($booking['booking_price'] ?? 0);
        if ($price <= 0) {
            // Fallback for old bookings created before booking_price column existed
            $amenityPriceRow = $db->table('amenities')->select('price')->where('id', $booking['amenity_id'])->get()->getRowArray();
            $price = (float) ($amenityPriceRow['price'] ?? 0);
        }
        if ($price <= 0) return;

        // Blindaje Lógico
        if (empty($booking['unit_id'])) return;

        $sourceStr = "booking_{$bookingId}";

        $transactionModel = new self();

        // Idempotencia: Buscar cargo existente (incluyendo eliminados)
        $existingCharge = $transactionModel->withDeleted()
            ->where('source', $sourceStr)
            ->where('type', 'charge')
            ->first();

        if ($existingCharge) {
            if (empty($existingCharge['deleted_at'])) {
                // Ya existe y está activo: no hacer nada
                return;
            } else {
                // Existe pero fue eliminado: Restaurarlo
                $db->table('financial_transactions')
                   ->where('id', $existingCharge['id'])
                   ->update([
                       'amount'     => $price,
                       'deleted_at' => null, // Restore
                       'status'     => 'pending', // Restore to pending
                   ]);
                return;
            }
        }

        // Obtener el nombre de la amenidad para la descripción
        $amenity = $db->table('amenities')->select('name')->where('id', $booking['amenity_id'])->get()->getRowArray();
        $amenityName = $amenity ? $amenity['name'] : 'Amenidad';
        $desc = "Reserva {$amenityName} - " . date('d/m/Y', strtotime($booking['start_time']));

        // Buscar o crear categoría 'Cargo de Reserva de Amenidad'
        $catName = 'Cargo de Reserva de Amenidad';
        $category = $db->table('financial_categories')
            ->where('condominium_id', $booking['condominium_id'])
            ->where('name', $catName)
            ->get()->getRowArray();

        $catId = null;
        if ($category) {
            $catId = $category['id'];
        } else {
            $db->table('financial_categories')->insert([
                'condominium_id' => $booking['condominium_id'],
                'name'           => $catName,
                'description'    => 'Cargo generado automáticamente por reserva de amenidad',
                'type'           => 'income',
                'is_system'      => 1
            ]);
            $catId = $db->insertID();
        }

        // Insertar nuevo cargo
        $transactionModel->insert([
            'condominium_id' => $booking['condominium_id'],
            'unit_id'        => $booking['unit_id'],
            'category_id'    => $catId,
            'type'           => 'charge',
            'amount'         => $price,
            'description'    => $desc,
            'due_date'       => date('Y-m-d', strtotime($booking['start_time'])),
            'status'         => 'pending',
            'source'         => $sourceStr,
        ]);
    }

    /**
     * Elimina lógicamente (soft-delete) el cargo de una reserva si se cancela o elimina.
     */
    public static function removeBookingCharge(int $bookingId): void
    {
        $transactionModel = new self();
        $sourceStr = "booking_{$bookingId}";

        $existingCharge = $transactionModel->where('source', $sourceStr)
            ->where('type', 'charge')
            ->first();

        if ($existingCharge) {
            // Actualizar status a cancelled para que desaparezca de FinanceController
            $transactionModel->update($existingCharge['id'], ['status' => 'cancelled']);
            $transactionModel->delete($existingCharge['id']);
        }
    }
}
