<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class AmenityDocumentModel extends Model
{
    protected $table            = 'amenity_documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'amenity_id',
        'title',
        'filename',
        'file_size',
        'file_type',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = '';

    /**
     * Obtener documentos de una amenidad
     */
    public function getByAmenity(int $amenityId): array
    {
        return $this->where('amenity_id', $amenityId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }
}
