<?php

namespace App\Models\Tenant;

class ResidentInvitationModel extends BaseTenantModel
{
    protected $table            = 'resident_invitations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // As per AXISCONDO technical spec, no ENUM checks here, just DB constraints/varchar
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'email',
        'name',
        'phone',
        'role', // 'owner', 'tenant', 'admin'
        'token',
        'invitation_status', // 'pending', 'accepted', 'expired', 'cancelled'
        'invited_by',
        'invited_at',
        'accepted_at',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
