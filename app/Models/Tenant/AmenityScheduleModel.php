<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class AmenityScheduleModel extends Model
{
    protected $table            = 'amenity_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'amenity_id',
        'day_of_week',
        'is_enabled',
        'open_time',
        'close_time',
    ];

    protected $useTimestamps = false;

    /**
     * Obtener horario completo de una amenidad
     */
    public function getScheduleByAmenity(int $amenityId): array
    {
        return $this->where('amenity_id', $amenityId)
                    ->orderBy('day_of_week', 'ASC')
                    ->findAll();
    }

    /**
     * Guardar/actualizar horario completo (7 días)
     */
    public function saveFullSchedule(int $amenityId, array $schedule): void
    {
        // Eliminar horario anterior
        $this->where('amenity_id', $amenityId)->delete();

        // Insertar nuevo horario
        foreach ($schedule as $day) {
            $this->insert([
                'amenity_id'  => $amenityId,
                'day_of_week' => $day['day_of_week'],
                'is_enabled'  => $day['is_enabled'] ?? 1,
                'open_time'   => $day['open_time'] ?? '09:00',
                'close_time'  => $day['close_time'] ?? '18:00',
            ]);
        }
    }
}
