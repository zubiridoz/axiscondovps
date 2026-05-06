<?php

namespace App\Models\Tenant;

/**
 * BlockedUserModel
 * 
 * Stores user-to-user blocks so blocked user's content
 * is hidden from the blocker's feed.
 * Apple App Store Guideline 1.2 compliance.
 */
class BlockedUserModel extends BaseTenantModel
{
    protected $table            = 'blocked_users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'condominium_id',
        'user_id',
        'blocked_user_id',
    ];

    protected $useTimestamps = false;

    /**
     * Get the list of user IDs blocked by a given user.
     */
    public function getBlockedIds(int $userId): array
    {
        $rows = $this->where('user_id', $userId)->findAll();
        return array_map(fn($r) => (int)$r['blocked_user_id'], $rows);
    }
}
