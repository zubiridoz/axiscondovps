<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

/**
 * CourierModel
 * 
 * Tabla global de proveedores de paquetería (no es tenant-scoped).
 */
class CourierModel extends Model
{
    protected $table            = 'couriers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'name',
        'icon',
        'sort_order',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
