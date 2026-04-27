<?php

namespace App\Models\Tenant;

class PollModel extends BaseTenantModel
{
    protected $table            = 'polls';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'is_active',
        'category',
        'hash_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateHashId'];

    protected function generateHashId(array $data): array
    {
        if (!isset($data['data']['hash_id'])) {
            $data['data']['hash_id'] = bin2hex(random_bytes(12));
        }
        return $data;
    }
}
