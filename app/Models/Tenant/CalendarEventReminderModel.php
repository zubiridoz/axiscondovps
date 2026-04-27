<?php

namespace App\Models\Tenant;

use CodeIgniter\Model;

class CalendarEventReminderModel extends Model
{
    protected $table            = 'calendar_event_reminders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'event_id',
        'minutes_before',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
