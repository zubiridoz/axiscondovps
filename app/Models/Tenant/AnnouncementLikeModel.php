<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class AnnouncementLikeModel extends Model
{
    protected $table            = 'announcement_likes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'announcement_id', 'user_id',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
