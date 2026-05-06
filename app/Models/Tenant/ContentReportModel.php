<?php

namespace App\Models\Tenant;

/**
 * ContentReportModel
 * 
 * Stores user reports of offensive content (posts / comments).
 * Apple App Store Guideline 1.2 compliance.
 */
class ContentReportModel extends BaseTenantModel
{
    protected $table            = 'content_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'condominium_id',
        'reporter_user_id',
        'reported_user_id',
        'announcement_id',
        'comment_id',
        'reason',
        'description',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
