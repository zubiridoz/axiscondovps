<?php

namespace App\Models\Tenant;

class BookingModel extends BaseTenantModel
{
    protected $table            = 'bookings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'amenity_id',
        'unit_id',
        'user_id',
        'start_time',
        'end_time',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /**
     * Genera un identificador hexadecimal corto (6 caracteres) a partir del ID de la reserva.
     * Usado en Flutter para mostrar "Reserva #043dfa" en lugar del ID numérico.
     */
    public static function generateShortHash(int $id): string
    {
        return substr(md5($id . 'cdnt_booking_salt_2026'), 0, 6);
    }

    /**
     * Enriquece un array de reserva (o un array de reservas) con el campo `short_hash`.
     *
     * @param array $bookings  Un solo booking o una lista de bookings.
     * @param bool  $isList    true si $bookings es una lista, false si es un solo registro.
     * @return array
     */
    public static function enrichWithHash(array $bookings, bool $isList = true): array
    {
        if ($isList) {
            return array_map(function ($b) {
                $b['short_hash'] = self::generateShortHash((int) $b['id']);
                return $b;
            }, $bookings);
        }

        // Single booking
        $bookings['short_hash'] = self::generateShortHash((int) $bookings['id']);
        return $bookings;
    }
}
