<?php

namespace App\Models\Tenant;

class UnitModel extends BaseTenantModel
{
    protected $table            = 'units';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'section_id',
        'unit_number',
        'floor',
        'type',
        'area',
        'indiviso_percentage',
        'maintenance_fee',
        'occupancy_type',
        'fee_start_month',
        'hash_id',
        'initial_balance'
    ];

    protected $beforeInsert = ['injectTenantId', 'generateHashId'];

    protected function generateHashId(array $data)
    {
        if (!isset($data['data']['hash_id'])) {
            $data['data']['hash_id'] = bin2hex(random_bytes(12));
        }
        return $data;
    }

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
