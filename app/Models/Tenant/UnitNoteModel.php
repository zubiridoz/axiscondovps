<?php

namespace App\Models\Tenant;

class UnitNoteModel extends BaseTenantModel
{
    protected $table            = 'unit_notes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'unit_id',
        'condominium_id',
        'user_id',
        'note'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtiene las notas de una unidad con el nombre del autor
     */
    public function getNotesWithUsers(int $unitId)
    {
        return $this->select('unit_notes.*, users.first_name, users.last_name')
                    ->join('users', 'users.id = unit_notes.user_id', 'left')
                    ->where('unit_id', $unitId)
                    ->orderBy('unit_notes.created_at', 'DESC')
                    ->findAll();
    }
}
