<?php

namespace App\Models\Tenant;

class ParcelModel extends BaseTenantModel
{
    protected $table            = 'parcels';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'received_by',
        'courier',
        'photo_url',
        'quantity',
        'parcel_type',
        'tracking_number',
        'status',
        'delivered_at',
        'picked_up_by',
        'picked_up_name',
        'signature_url'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
