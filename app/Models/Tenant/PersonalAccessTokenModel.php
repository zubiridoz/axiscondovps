<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class PersonalAccessTokenModel extends Model
{
    protected $table            = 'personal_access_tokens';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    // Este modelo no pertenece a un tenant específico sino a un usuario
    protected $allowedFields    = [
        'user_id',
        'token_hash',
        'device_name',
        'last_used_at',
        'expires_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
