<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class DevicePushSubscriptionModel extends Model
{
    protected $table            = 'device_push_subscriptions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'user_id',
        'condominium_id',
        'fcm_token',
        'device_info',
        'device_id',
        'endpoint',
        'p256dh_key',
        'auth_key',
        'platform',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
