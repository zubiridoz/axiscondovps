<?php

namespace App\Models\Tenant;

class CalendarEventModel extends BaseTenantModel
{
    protected $table            = 'calendar_events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'condominium_id',
        'title',
        'description',
        'location',
        'start_datetime',
        'end_datetime',
        'all_day',
        'is_internal',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
