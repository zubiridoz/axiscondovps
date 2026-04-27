<?php

namespace App\Models\Tenant;

class FinancialTransactionModel extends BaseTenantModel
{
    protected $table            = 'financial_transactions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'unit_id',
        'category_id',
        'extraordinary_fee_id',
        'type',
        'amount',
        'description',
        'due_date',
        'status',
        'attachment',
        'payment_method',
        'source'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
