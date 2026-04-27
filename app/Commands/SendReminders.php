<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\Tenant\PaymentReminderModel;
use App\Models\Tenant\PaymentReminderLogModel;
use App\Models\Tenant\CondominiumModel;
use App\Models\Tenant\UnitModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\ChargeModel; // Assuming this model exists to get pending charges
use App\Services\Notifications\PushNotificationService;
use App\Services\PaymentReminderService;

class SendReminders extends BaseCommand
{
    protected $group       = 'Reminders';
    protected $name        = 'reminders:send';
    protected $description = 'Sends automated payment reminders based on condominium settings.';

    public function run(array $params)
    {
        $condominiumModel = new CondominiumModel();
        $reminderModel = new PaymentReminderModel();
        $logModel = new PaymentReminderLogModel();
        
        $condominiums = $condominiumModel->where('status', 'active')->findAll();

        foreach ($condominiums as $condo) {
            // Set timezone to condominium's timezone
            $timezone = $condo['timezone'] ?? 'America/Mexico_City';
            date_default_timezone_set($timezone);

            // Fetch reminders, using service to auto-init defaults if empty
            $reminders = PaymentReminderService::getRemindersForCondominium($condo['id']);
            
            if (empty($reminders)) continue;

            $now = new \DateTime('now', new \DateTimeZone($timezone));
            
            // Only process if it's 10:00 AM (between 10:00 and 10:59)
            // Assuming cron runs hourly at :00, or daily at 10:00
            // Since cron is configured for 10:00, we just process it.

            $this->processCondominium($condo, $reminders, $now);
        }
        
        // Cleanup logs older than 30 days
        $thirtyDaysAgo = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        $logModel->where('created_at <', $thirtyDaysAgo)->delete();
        
        CLI::write("Reminders processed successfully.", 'green');
    }

    private function processCondominium($condo, $reminders, $now)
    {
        $logModel = new PaymentReminderLogModel();
        $unitModel = new UnitModel();
        $residentModel = new ResidentModel();
        $db = \Config\Database::connect();
        $chargeBuilder = $db->table('charges'); // Assuming charges table

        $todayStr = $now->format('Y-m-d');
        $currentDay = (int) $now->format('j');
        $endOfMonth = (int) $now->format('t');

        foreach ($reminders as $reminder) {
            if (!$reminder['is_active']) continue;

            $shouldSend = false;
            $dueDatesToFind = []; // Array of specific due dates we are looking for

            switch ($reminder['trigger_type']) {
                case 'start_of_month':
                    if ($currentDay === (int)$reminder['trigger_value']) {
                        $shouldSend = true;
                    }
                    break;
                case 'specific_day':
                    if ($currentDay === (int)$reminder['trigger_value']) {
                        $shouldSend = true;
                    }
                    break;
                case 'due_date':
                    // Need to find charges due exactly today
                    $dueDatesToFind[] = $todayStr;
                    $shouldSend = true; // Conditional based on charge
                    break;
                case 'days_before_due':
                    $targetDate = (clone $now)->modify('+' . $reminder['trigger_value'] . ' days')->format('Y-m-d');
                    $dueDatesToFind[] = $targetDate;
                    $shouldSend = true;
                    break;
                case 'days_after_due':
                    $targetDate = (clone $now)->modify('-' . $reminder['trigger_value'] . ' days')->format('Y-m-d');
                    $dueDatesToFind[] = $targetDate;
                    $shouldSend = true;
                    break;
            }

            if (!$shouldSend) continue;

            // Get all units with pending maintenance fees
            $units = $unitModel->where('condominium_id', $condo['id'])->findAll();
            
            foreach ($units as $unit) {
                // Determine if we need to send to this unit
                $unitNeedsReminder = false;

                if (in_array($reminder['trigger_type'], ['start_of_month', 'specific_day'])) {
                    // Start of month / specific day: send to everyone (or those who haven't paid if we want to be smart)
                    // For now, we send to everyone to remind them.
                    $unitNeedsReminder = true;
                } else {
                    // due_date, days_before_due, days_after_due depend on charge due dates
                    // Query charges for this unit
                    $charges = $chargeBuilder->where('unit_id', $unit['id'])
                                             ->where('status', 'pending')
                                             ->whereIn('due_date', $dueDatesToFind)
                                             ->get()->getResultArray();
                    
                    if (!empty($charges)) {
                        $unitNeedsReminder = true;
                    }
                }

                if ($unitNeedsReminder) {
                    // Send to all residents of this unit
                    $residents = $residentModel->where('unit_id', $unit['id'])
                                               ->where('status', 'active')
                                               ->findAll();

                    foreach ($residents as $res) {
                        // Check if already sent today for this reminder & user
                        $alreadySent = $logModel->where('reminder_id', $reminder['id'])
                                                ->where('user_id', $res['user_id'])
                                                ->where('sent_date', $todayStr)
                                                ->first();

                        if (!$alreadySent) {
                            $messageBody = str_replace('{x}', $reminder['trigger_value'], $reminder['message_body']);
                            
                            try {
                                // Send Push Notification
                                $pushService = new PushNotificationService();
                                $pushService->sendToUser(
                                    (int) $res['user_id'],
                                    $reminder['message_title'],
                                    $messageBody,
                                    ['type' => 'payment_reminder', 'unit_id' => $unit['id']]
                                );

                                // Log only if successful or at least attempted without catastrophic failure
                                $logModel->insert([
                                    'reminder_id' => $reminder['id'],
                                    'user_id'     => $res['user_id'],
                                    'sent_date'   => $todayStr
                                ]);
                            } catch (\Exception $e) {
                                // Log error internally but continue the loop
                                log_message('error', "Failed to send payment reminder push to user {$res['user_id']}: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }
}
