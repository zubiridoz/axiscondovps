<?php

namespace App\Models\Tenant;

class QrCodeModel extends BaseTenantModel
{
    protected $table            = 'qr_codes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'created_by',
        'visitor_id',
        'token',
        'visitor_name',
        'valid_from',
        'valid_until',
        'usage_limit',
        'times_used',
        'status',
        'visit_type',
        'vehicle_type',
        'vehicle_plate'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
