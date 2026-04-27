<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class PollVoteLogModel extends Model
{
    protected $table            = 'poll_vote_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'poll_id',
        'user_id',
        'previous_option_id',
        'new_option_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
