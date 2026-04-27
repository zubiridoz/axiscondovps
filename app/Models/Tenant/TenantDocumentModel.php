<?php

namespace App\Models\Tenant;

class TenantDocumentModel extends BaseTenantModel
{
    protected $table            = 'tenant_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    
    protected $allowedFields    = [
        'condominium_id',
        'hash_id',
        'parent_id',
        'type',
        'name',
        'path',
        'category',
        'access_level',
        'size_bytes',
        'mime_type',
        'uploaded_by',
        'is_starred',
    ];

    protected $beforeInsert = ['generateHashId'];

    protected function generateHashId(array $data)
    {
        if (empty($data['data']['hash_id'])) {
            $data['data']['hash_id'] = bin2hex(random_bytes(12));
        }
        return $data;
    }

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Configuración para BaseTenantModel
    protected $tenantColumn = 'condominium_id';
}
