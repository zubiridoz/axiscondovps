<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class ExtraordinaryFeeModel extends Model
{
    protected $table            = 'extraordinary_fees';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'condominium_id',
        'title',
        'description',
        'category_id',
        'amount',
        'expected_total',
        'start_date',
        'due_date',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
