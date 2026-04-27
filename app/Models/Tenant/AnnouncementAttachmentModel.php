<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class AnnouncementAttachmentModel extends Model
{
    protected $table            = 'announcement_attachments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;

    protected $allowedFields = [
        'announcement_id', 'file_name', 'original_name',
        'display_name', 'file_type', 'file_size', 'mime_type',
        'created_at',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
