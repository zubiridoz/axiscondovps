<?php

namespace App\Models\Tenant;

class AccessLogModel extends BaseTenantModel
{
    protected $table            = 'access_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'recorded_by',
        'qr_code_id',
        'visitor_id',
        'type',
        'visitor_type',
        'visit_type',
        'vehicle_type',
        'visitor_name',
        'plate_number',
        'photo_url',
        'photo_plate_url',
        'photo_exit_url',
        'entry_log_id',
        'gate_number',
        'notes',
    ];

    // Dates (AccessLog es una tabla inmutable, solo necesita created_at)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; 
}
