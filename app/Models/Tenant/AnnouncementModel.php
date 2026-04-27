<?php

namespace App\Models\Tenant;

class AnnouncementModel extends BaseTenantModel
{
    protected $table            = 'announcements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'created_by',
        'title',
        'content',
        'type',
        'category',
        'is_active',
        'allow_comments',
        'send_email',
        'email_target',
        'view_count',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
