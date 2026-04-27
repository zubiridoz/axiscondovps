<?php

namespace App\Models\Tenant;

class StaffMemberModel extends BaseTenantModel
{
    protected $table            = 'staff_members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'condominium_id',
        'first_name',
        'last_name',
        'staff_type',
        'device_id',
        'photo_url',
        'id_document_url',
        'status',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
