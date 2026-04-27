<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class CalendarEventReminderRecipientModel extends Model
{
    protected $table            = 'calendar_event_reminder_recipients';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'reminder_id',
        'user_id',
        'staff_member_id',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}
