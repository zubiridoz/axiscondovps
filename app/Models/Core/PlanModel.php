<?php

namespace App\Models\Core;

use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table            = 'plans';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'name',
        'slug',
        'max_condominiums',
        'min_units',
        'max_units',
        'price',
        'price_monthly',
        'price_yearly',
        'features',
        'status',
        'is_active',
        'sort_order',
        'stripe_price_id_monthly',
        'stripe_price_id_yearly'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
