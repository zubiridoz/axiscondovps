<?php

namespace App\Models\Tenant;

class AmenityModel extends BaseTenantModel
{
    protected $table            = 'amenities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'name',
        'description',
        'capacity',
        'is_active',
        'is_reservable',
        'price',
        'image',
        'rules',
        'open_time',
        'close_time',
        'reservation_interval',
        'max_active_reservations',
        'has_cost',
        'requires_approval',
        'available_from',
        'blocked_dates',
        'reservation_message',
        'hash_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
