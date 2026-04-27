<?php

namespace App\Models\Tenant;

class TicketModel extends BaseTenantModel
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'reported_by',
        'ticket_hash',
        'subject',
        'description',
        'category',
        'priority',
        'assigned_to_type',
        'assigned_to_id',
        'due_date',
        'tags',
        'location',
        'media_urls',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
