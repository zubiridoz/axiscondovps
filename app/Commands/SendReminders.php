<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\Notifications\PushNotificationService;
use App\Services\PaymentReminderService;

class SendReminders extends BaseCommand
{
    protected $group       = 'Reminders';
    protected $name        = 'reminders:send';
    protected $description = 'Sends automated payment reminders based on condominium settings.';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $condominiums = $db->table('condominiums')
            ->where('status', 'active')
            ->get()
            ->getResultArray();

        if (empty($condominiums)) {
            CLI::write('No active condominiums found.', 'yellow');
            return;
        }

        $processedCount = 0;
        $errorCount = 0;

        foreach ($condominiums as $condo) {
            try {
                // Set timezone to condominium's timezone
                $timezone = $condo['timezone'] ?? 'America/Mexico_City';
                date_default_timezone_set($timezone);

                // Fetch reminders, using service to auto-init defaults if empty
                $reminders = PaymentReminderService::getRemindersForCondominium($condo['id']);
                
                if (empty($reminders)) continue;

                $now = new \DateTime('now', new \DateTimeZone($timezone));
                
                $this->processCondominium($condo, $reminders, $now, $db);
                $processedCount++;
            } catch (\Exception $e) {
                $errorCount++;
                log_message('error', "[CRON SendReminders] Error para condo {$condo['id']}: " . $e->getMessage());
                CLI::write("Error processing condominium ID: {$condo['id']} - " . $e->getMessage(), 'red');
            }
        }
        
        // Cleanup logs older than 30 days
        $thirtyDaysAgo = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        $db->table('payment_reminder_logs')->where('created_at <', $thirtyDaysAgo)->delete();
        
        CLI::write("Reminders processing complete. Processed: {$processedCount}, Errors: {$errorCount}", 'green');
    }

    private function processCondominium($condo, $reminders, $now, $db)
    {
        $todayStr = $now->format('Y-m-d');
        $currentDay = (int) $now->format('j');

        foreach ($reminders as $reminder) {
            if (!$reminder['is_active']) continue;

            $shouldSend = false;
            $dueDatesToFind = [];

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
                    $dueDatesToFind[] = $todayStr;
                    $shouldSend = true;
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

            // Get all units for this condominium using direct DB access
            $units = $db->table('units')
                ->where('condominium_id', $condo['id'])
                ->get()
                ->getResultArray();
            
            foreach ($units as $unit) {
                $unitNeedsReminder = false;

                if (in_array($reminder['trigger_type'], ['start_of_month', 'specific_day'])) {
                    $unitNeedsReminder = true;
                } else {
                    // Check if unit has pending charges with matching due dates
                    $charges = $db->table('charges')
                        ->where('unit_id', $unit['id'])
                        ->where('status', 'pending')
                        ->whereIn('due_date', $dueDatesToFind)
                        ->get()
                        ->getResultArray();
                    
                    if (!empty($charges)) {
                        $unitNeedsReminder = true;
                    }
                }

                if ($unitNeedsReminder) {
                    // Get all active residents for this unit
                    $residents = $db->table('residents')
                        ->where('unit_id', $unit['id'])
                        ->where('status', 'active')
                        ->get()
                        ->getResultArray();

                    foreach ($residents as $resident) {
                        // Check if already sent today for this reminder & user
                        $alreadySent = $db->table('payment_reminder_logs')
                            ->where('reminder_id', $reminder['id'])
                            ->where('user_id', $resident['user_id'])
                            ->where('sent_date', $todayStr)
                            ->first();

                        if (!$alreadySent) {
                            $messageBody = str_replace('{x}', $reminder['trigger_value'], $reminder['message_body']);
                            
                            try {
                                // Send Push Notification
                                $pushService = new PushNotificationService();
                                $pushService->sendToUser(
                                    (int) $resident['user_id'],
                                    $reminder['message_title'],
                                    $messageBody,
                                    ['type' => 'payment_reminder', 'unit_id' => $unit['id']]
                                );

                                // Log the sent reminder
                                $db->table('payment_reminder_logs')->insert([
                                    'reminder_id' => $reminder['id'],
                                    'user_id'     => $resident['user_id'],
                                    'sent_date'   => $todayStr
                                ]);
                            } catch (\Exception $e) {
                                log_message('error', "Failed to send payment reminder push to user {$resident['user_id']}: " . $e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }
}
