<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\CalendarEventModel;
use App\Models\Tenant\CalendarEventReminderModel;
use App\Models\Tenant\CalendarEventReminderRecipientModel;
use App\Models\Tenant\UserCondominiumRoleModel;
use App\Models\Tenant\StaffMemberModel;

/**
 * CalendarController
 *
 * Gestión del Calendario Comunitario — CRUD de eventos con opciones avanzadas.
 */
class CalendarController extends BaseController
{
    /**
     * Renderiza la vista principal del calendario comunitario.
     */
    public function calendarView()
    {
        // [HACK LOCAL] Forzar contexto Tenant
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $eventModel = new CalendarEventModel();
        $events = $eventModel->orderBy('start_datetime', 'ASC')->findAll();

        // Enrich events with reminder counts
        $reminderModel = new CalendarEventReminderModel();
        $recipientModel = new CalendarEventReminderRecipientModel();

        foreach ($events as &$event) {
            $reminders = $reminderModel->where('event_id', $event['id'])->findAll();
            $event['reminder_count'] = count($reminders);
            $totalRecipients = 0;
            foreach ($reminders as $r) {
                $totalRecipients += $recipientModel->where('reminder_id', $r['id'])->countAllResults();
            }
            $event['recipient_count'] = $totalRecipients;
        }

        return view('admin/community_calendar', ['calendarEvents' => $events]);
    }

    /**
     * POST: Crear un nuevo evento.
     */
    public function create()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return $this->response->setJSON(['status' => 400, 'error' => 'El título es obligatorio']);
        }

        $allDay = (int) ($this->request->getPost('all_day') ?? 0);
        $startDate = $this->request->getPost('start_date');
        $endDate   = $this->request->getPost('end_date');

        if ($allDay) {
            $startDatetime = $startDate . ' 00:00:00';
            $endDatetime   = $endDate . ' 23:59:59';
        } else {
            $startHour   = str_pad($this->request->getPost('start_hour') ?? '8', 2, '0', STR_PAD_LEFT);
            $startMinute = str_pad($this->request->getPost('start_minute') ?? '0', 2, '0', STR_PAD_LEFT);
            $startAmpm   = strtoupper($this->request->getPost('start_ampm') ?? 'AM');
            $endHour     = str_pad($this->request->getPost('end_hour') ?? '9', 2, '0', STR_PAD_LEFT);
            $endMinute   = str_pad($this->request->getPost('end_minute') ?? '0', 2, '0', STR_PAD_LEFT);
            $endAmpm     = strtoupper($this->request->getPost('end_ampm') ?? 'AM');

            $startDatetime = $startDate . ' ' . $this->to24h($startHour, $startMinute, $startAmpm);
            $endDatetime   = $endDate . ' ' . $this->to24h($endHour, $endMinute, $endAmpm);
        }

        $eventModel = new CalendarEventModel();
        $eventData = [
            'title'          => $title,
            'description'    => $this->request->getPost('description') ?? null,
            'location'       => $this->request->getPost('location') ?? null,
            'start_datetime' => $startDatetime,
            'end_datetime'   => $endDatetime,
            'all_day'        => $allDay,
            'is_internal'    => (int) ($this->request->getPost('is_internal') ?? 0),
            'created_by'     => session()->get('user_id') ?? null,
        ];

        $eventId = $eventModel->insert($eventData);

        if (!$eventId) {
            return $this->response->setJSON(['status' => 500, 'error' => 'Error al crear el evento']);
        }

        // Process reminders
        $this->saveReminders($eventId);

        // 🔔 Notificación push + in-app a todos los residentes (solo eventos públicos)
        $isInternal = (int) ($this->request->getPost('is_internal') ?? 0);
        if (!$isInternal) {
            $this->dispatchCalendarPush($eventId, $title, $eventData['start_datetime']);
        }

        return $this->response->setJSON([
            'status'  => 201,
            'message' => 'Evento creado exitosamente',
            'id'      => $eventId
        ]);
    }

    /**
     * POST: Actualizar un evento existente.
     */
    public function update($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $eventModel = new CalendarEventModel();
        $event = $eventModel->find($id);
        if (!$event) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Evento no encontrado']);
        }

        $title = trim($this->request->getPost('title') ?? '');
        if ($title === '') {
            return $this->response->setJSON(['status' => 400, 'error' => 'El título es obligatorio']);
        }

        $allDay = (int) ($this->request->getPost('all_day') ?? 0);
        $startDate = $this->request->getPost('start_date');
        $endDate   = $this->request->getPost('end_date');

        if ($allDay) {
            $startDatetime = $startDate . ' 00:00:00';
            $endDatetime   = $endDate . ' 23:59:59';
        } else {
            $startHour   = str_pad($this->request->getPost('start_hour') ?? '8', 2, '0', STR_PAD_LEFT);
            $startMinute = str_pad($this->request->getPost('start_minute') ?? '0', 2, '0', STR_PAD_LEFT);
            $startAmpm   = strtoupper($this->request->getPost('start_ampm') ?? 'AM');
            $endHour     = str_pad($this->request->getPost('end_hour') ?? '9', 2, '0', STR_PAD_LEFT);
            $endMinute   = str_pad($this->request->getPost('end_minute') ?? '0', 2, '0', STR_PAD_LEFT);
            $endAmpm     = strtoupper($this->request->getPost('end_ampm') ?? 'AM');

            $startDatetime = $startDate . ' ' . $this->to24h($startHour, $startMinute, $startAmpm);
            $endDatetime   = $endDate . ' ' . $this->to24h($endHour, $endMinute, $endAmpm);
        }

        $eventModel->update($id, [
            'title'          => $title,
            'description'    => $this->request->getPost('description') ?? null,
            'location'       => $this->request->getPost('location') ?? null,
            'start_datetime' => $startDatetime,
            'end_datetime'   => $endDatetime,
            'all_day'        => $allDay,
            'is_internal'    => (int) ($this->request->getPost('is_internal') ?? 0),
        ]);

        // Rebuild reminders
        $this->deleteReminders($id);
        $this->saveReminders($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Evento actualizado exitosamente']);
    }

    /**
     * POST: Eliminar un evento (soft delete).
     */
    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $eventModel = new CalendarEventModel();
        $event = $eventModel->find($id);
        if (!$event) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Evento no encontrado']);
        }

        // Delete reminders first (cascade should handle but be explicit)
        $this->deleteReminders($id);
        $eventModel->delete($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Evento eliminado exitosamente']);
    }

    /**
     * GET: Devuelve detalle completo de un evento.
     */
    public function getEvent($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $eventModel = new CalendarEventModel();
        $event = $eventModel->find($id);
        if (!$event) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Evento no encontrado']);
        }

        // Get reminders with recipients
        $reminderModel = new CalendarEventReminderModel();
        $recipientModel = new CalendarEventReminderRecipientModel();

        $reminders = $reminderModel->where('event_id', $id)->findAll();
        $remindersData = [];

        foreach ($reminders as $reminder) {
            $recipients = $recipientModel->where('reminder_id', $reminder['id'])->findAll();
            $recipientIds = [];
            foreach ($recipients as $rec) {
                if (!empty($rec['user_id'])) {
                    $recipientIds[] = 'user_' . $rec['user_id'];
                }
                if (!empty($rec['staff_member_id'])) {
                    $recipientIds[] = 'staff_' . $rec['staff_member_id'];
                }
            }
            $remindersData[] = [
                'id'             => $reminder['id'],
                'minutes_before' => (int) $reminder['minutes_before'],
                'recipients'     => $recipientIds,
            ];
        }

        $event['reminders'] = $remindersData;

        return $this->response->setJSON(['status' => 200, 'data' => $event]);
    }

    /**
     * GET: Devuelve la lista de posibles destinatarios (admins + staff).
     */
    public function getRecipients()
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $recipients = [];

        // Admins (role_id = 2) from user_condominium_roles
        $db = \Config\Database::connect();
        $admins = $db->table('user_condominium_roles')
                     ->select('users.id, users.first_name, users.last_name')
                     ->join('users', 'users.id = user_condominium_roles.user_id')
                     ->where('user_condominium_roles.condominium_id', $tenantId)
                     ->where('user_condominium_roles.role_id', 2)
                     ->get()->getResultArray();

        foreach ($admins as $admin) {
            $recipients[] = [
                'id'    => 'user_' . $admin['id'],
                'name'  => trim($admin['first_name'] . ' ' . $admin['last_name']),
                'type'  => 'Administrador',
            ];
        }

        // Staff members
        $staffModel = new StaffMemberModel();
        $staffMembers = $staffModel->where('status', 'active')->findAll();

        foreach ($staffMembers as $staff) {
            $recipients[] = [
                'id'    => 'staff_' . $staff['id'],
                'name'  => trim($staff['first_name'] . ' ' . $staff['last_name']),
                'type'  => 'Staff',
            ];
        }

        return $this->response->setJSON(['status' => 200, 'data' => $recipients]);
    }

    // ─── Helpers ─────────────────────────────────────────────

    /**
     * Convert 12h format to 24h string HH:MM:SS
     */
    private function to24h(string $hour, string $minute, string $ampm): string
    {
        $h = (int) $hour;
        if ($ampm === 'PM' && $h < 12) $h += 12;
        if ($ampm === 'AM' && $h === 12) $h = 0;
        return str_pad($h, 2, '0', STR_PAD_LEFT) . ':' . $minute . ':00';
    }

    /**
     * Save reminders from POST data for a given event.
     */
    private function saveReminders(int $eventId): void
    {
        $reminderModel    = new CalendarEventReminderModel();
        $recipientModel   = new CalendarEventReminderRecipientModel();

        $remindersJson = $this->request->getPost('reminders');
        if (!$remindersJson) return;

        $reminders = json_decode($remindersJson, true);
        if (!is_array($reminders)) return;

        $recipientsRaw = $this->request->getPost('recipients');
        $recipientIds  = [];
        if ($recipientsRaw) {
            $recipientIds = is_array($recipientsRaw) ? $recipientsRaw : explode(',', $recipientsRaw);
        }

        foreach ($reminders as $rem) {
            $minutesBefore = (int) ($rem['minutes_before'] ?? 30);
            $remId = $reminderModel->insert([
                'event_id'       => $eventId,
                'minutes_before' => $minutesBefore,
            ]);

            // Link recipients to this reminder
            foreach ($recipientIds as $rid) {
                $rid = trim($rid);
                if (strpos($rid, 'user_') === 0) {
                    $recipientModel->insert([
                        'reminder_id'    => $remId,
                        'user_id'        => (int) str_replace('user_', '', $rid),
                        'staff_member_id' => null,
                        'created_at'     => date('Y-m-d H:i:s'),
                    ]);
                } elseif (strpos($rid, 'staff_') === 0) {
                    $recipientModel->insert([
                        'reminder_id'    => $remId,
                        'user_id'        => null,
                        'staff_member_id' => (int) str_replace('staff_', '', $rid),
                        'created_at'      => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    /**
     * Delete all reminders (and their recipients via cascade) for an event.
     */
    private function deleteReminders(int $eventId): void
    {
        $reminderModel  = new CalendarEventReminderModel();
        $recipientModel = new CalendarEventReminderRecipientModel();

        $reminders = $reminderModel->where('event_id', $eventId)->findAll();
        foreach ($reminders as $r) {
            $recipientModel->where('reminder_id', $r['id'])->delete();
        }
        $reminderModel->where('event_id', $eventId)->delete();
    }

    /**
     * 🔔 Push + In-App notification a TODOS los residentes cuando admin crea un evento.
     * Sigue el mismo patrón de AnnouncementController::dispatchAnnouncementPush
     */
    private function dispatchCalendarPush(int $eventId, string $title, string $startDatetime): void
    {
        log_message('info', '[CALENDAR_PUSH] ========== DISPATCH START ==========');
        log_message('info', "[CALENDAR_PUSH] Event #{$eventId}: {$title}");

        try {
            $condominiumId = \App\Services\TenantService::getInstance()->getTenantId();
            if (!$condominiumId) {
                log_message('warning', '[CALENDAR_PUSH] No tenant ID — aborting');
                return;
            }

            $db = \Config\Database::connect();

            // Obtener todos los user_id de residentes
            $residents = $db->table('residents')
                ->select('user_id')
                ->where('condominium_id', $condominiumId)
                ->where('user_id IS NOT NULL')
                ->get()->getResultArray();

            log_message('info', '[CALENDAR_PUSH] Residents found: ' . count($residents));

            if (empty($residents)) {
                log_message('warning', '[CALENDAR_PUSH] No residents — aborting');
                return;
            }

            // Obtener nombre del condominio
            $condoRow = $db->table('condominiums')->select('name')->where('id', $condominiumId)->get()->getRowArray();
            $condoName = $condoRow['name'] ?? 'Mi Condominio';

            // Formatear fecha legible
            $dateFormatted = '';
            try {
                $dt = new \DateTime($startDatetime);
                $dateFormatted = $dt->format('d/m/Y');
            } catch (\Throwable $e) {
                $dateFormatted = $startDatetime;
            }

            $pushTitle = "📅 Nuevo Evento · {$condoName}";
            $pushBody  = "{$title} — {$dateFormatted}";

            // Insertar notificaciones in-app para cada residente
            $now = date('Y-m-d H:i:s');
            $insertedCount = 0;

            foreach ($residents as $r) {
                $inserted = $db->table('notifications')->insert([
                    'condominium_id' => $condominiumId,
                    'user_id'        => $r['user_id'],
                    'type'           => 'calendar_event',
                    'title'          => $pushTitle,
                    'body'           => $pushBody,
                    'data'           => json_encode([
                        'event_id' => $eventId,
                        'type'     => 'calendar',
                    ]),
                    'read_at'    => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                if ($inserted) $insertedCount++;
            }

            log_message('info', "[CALENDAR_PUSH] ✅ In-app notifications inserted: {$insertedCount}/" . count($residents));

            // Verificar tokens FCM
            $tokenCount = $db->table('device_push_subscriptions')
                ->where('condominium_id', $condominiumId)
                ->where('fcm_token IS NOT NULL')
                ->where('fcm_token !=', '')
                ->countAllResults();

            log_message('info', "[CALENDAR_PUSH] FCM tokens available: {$tokenCount}");

            if ($tokenCount === 0) {
                log_message('warning', '[CALENDAR_PUSH] ⚠️ No FCM tokens — push NOT sent (DB notifications saved)');
                return;
            }

            // Enviar push FCM
            $pushService = new \App\Services\Notifications\PushNotificationService();
            $result = $pushService->sendToCondominium($condominiumId, $pushTitle, $pushBody, [
                'type'         => 'calendar',
                'event_id'     => (string) $eventId,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]);

            log_message('info', '[CALENDAR_PUSH] FCM send result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            log_message('info', '[CALENDAR_PUSH] ========== DISPATCH END ==========');

        } catch (\Throwable $e) {
            log_message('error', '[CALENDAR_PUSH] ❌ Exception: ' . $e->getMessage());
            log_message('error', '[CALENDAR_PUSH] Stack: ' . $e->getTraceAsString());
        }
    }
}
