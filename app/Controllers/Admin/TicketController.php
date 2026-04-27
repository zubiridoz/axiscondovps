<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\TicketModel;

/**
 * TicketController
 *
 * Tickets module with three admin views:
 * - Lista
 * - Panel
 * - Metricas
 */
class TicketController extends BaseController
{
    public function index()
    {
        return $this->renderTicketsView('lista');
    }

    public function panel()
    {
        return $this->renderTicketsView('panel');
    }

    public function metrics()
    {
        return $this->renderTicketsView('metricas');
    }

    /**
     * Vista de Detalles Completa del Ticket (Reporte Individual)
     */
    public function detail($hash = null)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        if (!$hash) return redirect()->to(base_url('admin/tickets'));

        $ticketModel = new TicketModel();
        
        // Queries with Strict Tenant Isolation!
        if (is_numeric($hash) && strlen($hash) < 10) {
            $ticketInfo = $ticketModel->where('condominium_id', $tenantId)->where('id', $hash)->first();
        } else {
            $ticketInfo = $ticketModel->where('condominium_id', $tenantId)->where('ticket_hash', $hash)->first();
        }

        if (!$ticketInfo) return redirect()->to(base_url('admin/tickets'))->with('error', 'Ticket no encontrado');

        // Auto-upgrade legacy or corrupted tickets to 24-character ObjectIDs
        if (empty($ticketInfo['ticket_hash']) || strlen($ticketInfo['ticket_hash']) < 24) {
             $newHash = substr(bin2hex(random_bytes(16)), 0, 24);
             $ticketModel->update($ticketInfo['id'], ['ticket_hash' => $newHash]);
             return redirect()->to(base_url('admin/tickets/' . $newHash));
        }

        $shortHash = substr($ticketInfo['ticket_hash'], -6);

        $data = [
            'ticket' => $this->normalizeTicket($ticketInfo),
            'page_title' => 'Reporte #' . $shortHash,
            'current_view' => 'detalle'
        ];

        return view('admin/ticket_detail', $data);
    }

    /**
     * Crea un nuevo ticket
     */
    public function create()
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $assignedStr = $this->request->getPost('assigned_to');
        $assignType = null;
        $assignId = null;
        if (!empty($assignedStr) && str_contains($assignedStr, '_')) {
            list($assignType, $assignId) = explode('_', $assignedStr);
        }

        $dueDateStr = $this->request->getPost('due_date');
        $dueDate = !empty($dueDateStr) ? date('Y-m-d 23:59:59', strtotime($dueDateStr)) : null;
        
        // Handling media uploads
        $mediaUrls = [];
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            $uploadDir = WRITEPATH . 'uploads/tickets';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $ext = $file->getExtension();
                    $newName = 'tk_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $file->move($uploadDir, $newName);
                    $mediaUrls[] = 'writable/uploads/tickets/' . $newName;
                    if (count($mediaUrls) >= 7) break; // Limit to 7 serverside
                }
            }
        }

        $desc = $this->request->getPost('description');
        $subject = $this->request->getPost('subject');
        if (empty($subject)) {
             $subject = mb_strlen($desc) > 50 ? mb_substr($desc, 0, 47) . '...' : ($desc ?: 'Sin Asunto');
        }

        $data = [
            'condominium_id'   => $tenantId,
            'unit_id'          => $this->request->getPost('unit_id') ?: null,
            'reported_by'      => session()->get('user_id') ?? 1,
            'ticket_hash'      => substr(bin2hex(random_bytes(16)), 0, 24),
            'subject'          => $subject,
            'description'      => $desc,
            'category'         => $this->request->getPost('category') ?: 'Otro',
            'priority'         => $this->request->getPost('priority') ?: 'medium',
            'assigned_to_type' => $assignType,
            'assigned_to_id'   => $assignId,
            'due_date'         => $dueDate,
            'tags'             => $this->request->getPost('tags') ?: null,
            'location'         => $this->request->getPost('location') ?: null,
            'media_urls'       => !empty($mediaUrls) ? json_encode($mediaUrls) : null,
            'status'           => 'open',
        ];

        $ticketModel = new TicketModel();
        $ticketId = $ticketModel->insert($data);
        
        if (!$ticketId) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Error al guardar. ' . implode(', ', $ticketModel->errors())]);
        }

        return $this->response->setJSON(['status' => 201, 'message' => 'Ticket creado exitosamente', 'id' => $ticketId, 'hash' => $data['ticket_hash']]);
    }

    /**
     * Actualiza Detalles del Ticket (Asignar, Prioridad, Estatus)
     */
    public function updateDetails($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $ticketModel = new TicketModel();
        if (!$ticketModel->find($id)) return $this->response->setJSON(['status' => 404, 'error' => 'Ticket no encontrado']);

        $update = [];
        $status = $this->request->getPost('status');
        $priority = $this->request->getPost('priority');
        $assignedStr = $this->request->getPost('assigned_to');
        
        if ($status) $update['status'] = $status;
        if ($priority) $update['priority'] = $priority;
        if ($assignedStr !== null) {
            if ($assignedStr === '') {
                $update['assigned_to_type'] = null;
                $update['assigned_to_id'] = null;
            } else if (str_contains($assignedStr, '_')) {
                list($assignType, $assignId) = explode('_', $assignedStr);
                $update['assigned_to_type'] = $assignType;
                $update['assigned_to_id'] = $assignId;
            }
        }

        if (!empty($update)) {
            $ticketModel->update($id, $update);
        }

        // ── Notificar al residente si el admin resolvió/cerró el ticket ──
        if (isset($update['status']) && in_array($update['status'], ['resolved', 'closed'])) {
            $ticket = $ticketModel->find($id);
            if ($ticket && !empty($ticket['reported_by'])) {
                $statusLabel = $update['status'] === 'resolved' ? 'resuelto' : 'cerrado';
                $this->notifyTicketResident(
                    (int) $ticket['condominium_id'],
                    (int) $ticket['reported_by'],
                    '✅ Tu reporte fue ' . $statusLabel,
                    'Tu reporte "' . mb_substr($ticket['subject'] ?? '', 0, 60) . '" ha sido marcado como ' . $statusLabel . '.',
                    ['ticket_id' => $id, 'type' => 'ticket_' . $update['status']]
                );
            }
        }

        return $this->response->setJSON(['status' => 200, 'message' => 'Ticket actualizado correctamente']);
    }

    /**
     * Eliminar ticket (Soft Delete / Hard Delete)
     */
    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);
        
        $ticketModel = new TicketModel();
        if (!$ticketModel->find($id)) return $this->response->setJSON(['status' => 404, 'error' => 'Ticket no encontrado']);
        
        // This will attempt to use CI4's soft delete if useSoftDeletes is configured, otherwise hard delete
        if ($ticketModel->delete($id)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Ticket eliminado exitosamente']);
        }

        return $this->response->setJSON(['status' => 400, 'error' => 'Error al eliminar ticket']);
    }

    private function fetchAssignees(): array
    {
        $this->bootstrapTenant();
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();
        
        $admins = $db->table('users')
                     ->distinct()
                     ->select('users.id, users.first_name, users.last_name')
                     ->join('user_condominium_roles ucr', 'ucr.user_id = users.id')
                     ->join('roles r', 'r.id = ucr.role_id')
                     ->groupStart()
                         ->where('ucr.condominium_id', $condoId)
                         ->orWhere('ucr.condominium_id IS NULL')
                     ->groupEnd()
                     ->whereIn('r.name', ['ADMIN', 'SUPER_ADMIN'])
                     ->get()
                     ->getResultArray();
                     
        $staff = $db->table('staff_members')
                    ->select('id, first_name, last_name, staff_type')
                    ->where('condominium_id', $condoId)
                    ->get()
                    ->getResultArray();

        return ['admins' => $admins, 'staff' => $staff];
    }

    /**
     * Obtener listado para Dropdown de Responsables
     */
    public function getAssignees()
    {
        return $this->response->setJSON(array_merge(['status' => 200], $this->fetchAssignees()));
    }

    /**
     * Obtener mensajes de conversación de un ticket
     */
    public function getMessages($ticketId = null)
    {
        if (!$ticketId) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $this->bootstrapTenant();
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();

        $rows = $db->table('ticket_comments tc')
            ->select('tc.*, u.first_name, u.last_name')
            ->join('users u', 'u.id = tc.user_id', 'left')
            ->where('tc.ticket_id', $ticketId)
            ->where('tc.condominium_id', $condoId)
            ->where('tc.deleted_at IS NULL')
            ->orderBy('tc.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $messages = [];
        foreach ($rows as $row) {
            $name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
            if ($name === '') $name = 'Administrador';
            $initials = strtoupper(substr($row['first_name'] ?? 'A', 0, 1) . substr($row['last_name'] ?? '', 0, 1));
            
            $mediaUrls = $row['media_urls'] ? json_decode($row['media_urls'], true) : [];
            $mediaOut = [];
            foreach ($mediaUrls as $mUrl) {
                $mediaOut[] = base_url('admin/tickets/media/' . basename($mUrl));
            }

            $createdAt = strtotime($row['created_at'] ?? 'now');
            $diff = time() - $createdAt;
            if ($diff < 60) $timeLabel = 'hace un momento';
            elseif ($diff < 3600) $timeLabel = 'hace ' . (int) floor($diff / 60) . ' minutos';
            elseif ($diff < 86400) $timeLabel = 'hace ' . (int) floor($diff / 3600) . ' horas';
            else $timeLabel = date('d M Y H:i', $createdAt);

            $messages[] = [
                'id'        => (int) $row['id'],
                'user_id'   => (int) $row['user_id'],
                'name'      => $name,
                'initials'  => $initials ?: 'AD',
                'message'   => $row['message'],
                'type'      => $row['type'],
                'media_urls' => $mediaOut,
                'time_label' => $timeLabel,
                'date_label' => date('Y-m-d', $createdAt) === date('Y-m-d') ? 'Hoy' : date('d M Y', $createdAt),
                'created_at' => $row['created_at'],
            ];
        }

        return $this->response->setJSON([
            'status'   => 200,
            'messages' => $messages,
            'count'    => count($messages),
            'reply_count' => count(array_filter($messages, fn($m) => $m['type'] === 'reply')),
            'internal_count' => count(array_filter($messages, fn($m) => $m['type'] === 'internal')),
        ]);
    }

    /**
     * Enviar un mensaje en la conversación del ticket
     */
    public function sendMessage($ticketId = null)
    {
        if (!$ticketId) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $this->bootstrapTenant();
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();

        $message = trim((string) $this->request->getPost('message'));
        $type = $this->request->getPost('type') === 'internal' ? 'internal' : 'reply';

        if ($message === '' && empty($this->request->getFileMultiple('media'))) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El mensaje no puede estar vacío']);
        }

        // Handle media uploads
        $mediaUrls = [];
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            $uploadDir = WRITEPATH . 'uploads/tickets';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $ext = $file->getExtension();
                    $newName = 'msg_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $file->move($uploadDir, $newName);
                    $mediaUrls[] = 'writable/uploads/tickets/' . $newName;
                    if (count($mediaUrls) >= 5) break;
                }
            }
        }

        $commentModel = new \App\Models\Tenant\TicketCommentModel();
        $data = [
            'ticket_id'      => $ticketId,
            'condominium_id' => $condoId,
            'user_id'        => session()->get('user_id') ?? 1,
            'message'        => $message,
            'type'           => $type,
            'media_urls'     => !empty($mediaUrls) ? json_encode($mediaUrls) : null,
        ];

        $commentId = $commentModel->insert($data);
        if (!$commentId) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Error al guardar: ' . implode(', ', $commentModel->errors())]);
        }

        // ── Notificar al residente si es un reply (no nota interna) ──
        if ($type === 'reply') {
            $ticket = (new TicketModel())->find($ticketId);
            $adminUserId = session()->get('user_id') ?? 1;
            if ($ticket && !empty($ticket['reported_by']) && (int) $ticket['reported_by'] !== (int) $adminUserId) {
                $adminName = $this->getAdminName($adminUserId);
                $this->notifyTicketResident(
                    (int) $ticket['condominium_id'],
                    (int) $ticket['reported_by'],
                    '💬 ' . $adminName . ' respondió tu reporte',
                    mb_substr($message ?: '📷 Adjunto enviado', 0, 120),
                    ['ticket_id' => $ticketId, 'type' => 'ticket_admin_comment']
                );
            }
        }

        return $this->response->setJSON([
            'status'  => 201,
            'message' => 'Mensaje enviado',
            'id'      => $commentId,
        ]);
    }

    private function renderTicketsView(string $currentView)
    {
        $this->bootstrapTenant();

        $ticketModel = new TicketModel();
        $rows = $ticketModel
            ->select('tickets.*, units.unit_number as unit_name, users.first_name, users.last_name, staff.first_name as staff_fname, staff.last_name as staff_lname, assigned_users.first_name as user_fname, assigned_users.last_name as user_lname')
            ->join('units', 'units.id = tickets.unit_id', 'left')
            ->join('users', 'users.id = tickets.reported_by', 'left')
            ->join('users as assigned_users', 'assigned_users.id = tickets.assigned_to_id AND tickets.assigned_to_type = "user"', 'left')
            ->join('staff_members as staff', 'staff.id = tickets.assigned_to_id AND tickets.assigned_to_type = "staff"', 'left')
            ->orderBy('tickets.created_at', 'DESC')
            ->findAll();

        $normalizedTickets = array_map(function (array $row): array {
            return $this->normalizeTicket($row);
        }, $rows);

        $tray = strtolower((string) $this->request->getGet('bandeja'));
        $tray = $tray === 'archivo' ? 'archivo' : 'activos';

        $activeTickets = array_values(array_filter(
            $normalizedTickets,
            static fn(array $ticket): bool => $ticket['bucket'] === 'active'
        ));

        $archiveTickets = array_values(array_filter(
            $normalizedTickets,
            static fn(array $ticket): bool => $ticket['bucket'] === 'archive'
        ));

        $listTickets = $tray === 'archivo' ? $archiveTickets : $activeTickets;

        $period = strtolower((string) $this->request->getGet('periodo'));
        if (!in_array($period, ['semana', 'mes', 'trimestre', 'anio'], true)) {
            $period = 'mes';
        }

        [$periodStart, $periodEnd, $bucketCount] = $this->resolvePeriod($period);
        $periodTickets = array_values(array_filter($normalizedTickets, static function (array $ticket) use ($periodStart, $periodEnd): bool {
            return $ticket['created_ts'] >= $periodStart && $ticket['created_ts'] <= $periodEnd;
        }));

        $totals = $this->buildTotals($normalizedTickets, $activeTickets);
        $aging = $this->buildAgingBreakdown($activeTickets);
        $alerts = $this->buildAlerts($totals);
        $trend = $this->buildTrendSeries($normalizedTickets, $periodStart, $periodEnd, $bucketCount);
        $categories = $this->buildCategoryDistribution($periodTickets);
        $resolution = $this->buildResolutionStats($periodTickets);
        $assignees = $this->fetchAssignees();
        $staffMetrics = $this->buildStaffMetrics($periodTickets, $assignees);

        return view('admin/tickets', [
            'current_view' => $currentView,
            'tray' => $tray,
            'tickets' => $listTickets,
            'active_count' => count($activeTickets),
            'archive_count' => count($archiveTickets),
            'totals' => $totals,
            'aging' => $aging,
            'alerts' => $alerts,
            'period' => $period,
            'trend' => $trend,
            'categories' => $categories,
            'resolution' => $resolution,
            'assignees' => $assignees,
            'staffMetrics' => $staffMetrics,
        ]);
    }

    private function bootstrapTenant(): void
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) {
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);
        }
    }

    private function normalizeTicket(array $row): array
    {
        $createdTs = strtotime((string) ($row['created_at'] ?? '')) ?: time();
        $updatedTs = strtotime((string) ($row['updated_at'] ?? '')) ?: $createdTs;
        $now = time();
        $status = (string) ($row['status'] ?? 'open');

        $statusLabelMap = [
            'open' => 'Nuevo',
            'in_progress' => 'En progreso',
            'resolved' => 'Resuelto',
            'closed' => 'Cerrado',
        ];
        $statusClassMap = [
            'open' => 'bg-info text-white',
            'in_progress' => 'bg-warning text-dark',
            'resolved' => 'bg-success text-white',
            'closed' => 'bg-secondary text-white',
        ];

        $fullName = trim((string) (($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')));
        if ($fullName === '') {
            $fullName = 'Residente';
        }

        $ageHours = (int) floor(max(0, ($now - $createdTs) / 3600));
        
        $priority = strtolower((string) ($row['priority'] ?? 'medium'));
        $priorityLabelMap = ['low' => 'Bajo', 'medium' => 'Medio', 'high' => 'Alto', 'critical' => 'Crítico'];
        $priorityClassMap = [
            'low' => 'bg-success text-white', 
            'medium' => 'bg-warning text-dark', 
            'high' => 'bg-orange text-white', 
            'critical' => 'bg-danger text-white'
        ];
        // Custom orange for high
        if ($priority === 'high') {
             $priorityClass = 'bg-warning text-dark'; // TBD via CSS, using warning for now
        } else {
             $priorityClass = $priorityClassMap[$priority] ?? 'bg-secondary';
        }
        $priorityLabel = $priorityLabelMap[$priority] ?? 'Medio';
        
        $category = (string) ($row['category'] ?? '');
        if ($category === '') $category = $this->resolveCategory((string) ($row['subject'] ?? ''), (string) ($row['description'] ?? ''));

        $stateSeconds = in_array($status, ['resolved', 'closed'], true) ? max(0, $updatedTs - $createdTs) : max(0, $now - $createdTs);
        
        $assignedToName = 'Por asignar';
        if (($row['assigned_to_type'] ?? '') === 'staff') {
            $assignedToName = trim(($row['staff_fname'] ?? '') . ' ' . ($row['staff_lname'] ?? ''));
        } elseif (($row['assigned_to_type'] ?? '') === 'user') {
            $assignedToName = trim(($row['user_fname'] ?? '') . ' ' . ($row['user_lname'] ?? ''));
        }

        $mediaUrlsStr = $row['media_urls'] ?? null;
        $mediaUrls = $mediaUrlsStr ? json_decode($mediaUrlsStr, true) : [];

        return [
            'id' => (int) ($row['id'] ?? 0),
            'hash' => $row['ticket_hash'] ?? ('000' . ($row['id'] ?? 0)),
            'subject' => trim((string) ($row['subject'] ?? 'Sin asunto')),
            'description' => trim((string) ($row['description'] ?? '')),
            'unit_name' => trim((string) ($row['unit_name'] ?? 'Sin unidad')),
            'reporter' => $fullName,
            'reporter_initials' => $this->extractInitials($fullName),
            'status' => $status,
            'status_label' => $statusLabelMap[$status] ?? 'Desconocido',
            'status_class' => $statusClassMap[$status] ?? 'bg-secondary text-white',
            'category' => $category,
            'priority_value' => $priority,
            'priority' => $priorityLabel,
            'priority_class' => $priorityClass,
            'assigned_to_name' => $assignedToName,
            'assigned_to_type' => $row['assigned_to_type'] ?? '',
            'assigned_to_id' => $row['assigned_to_id'] ?? '',
            'due_date' => $row['due_date'] ?? '',
            'tags' => $row['tags'] ?? '',
            'location' => $row['location'] ?? '',
            'media_urls' => $mediaUrls,
            'created_at_label' => date('M d H:i', $createdTs),
            'created_ts' => $createdTs,
            'updated_ts' => $updatedTs,
            'time_in_state' => $this->toShortDuration($stateSeconds),
            'open_duration' => $this->toShortDuration(max(0, $now - $createdTs)),
            'last_activity' => 'hace ' . $this->toShortDuration(max(0, $now - $updatedTs)),
            'bucket' => in_array($status, ['resolved', 'closed'], true) ? 'archive' : 'active',
            'is_attention' => in_array($status, ['open', 'in_progress'], true) && $ageHours >= 48,
            'is_overdue' => $status === 'open' && $ageHours >= 24,
            'search' => strtolower(trim(implode(' ', [
                substr($row['ticket_hash'] ?? ('000' . ($row['id'] ?? 0)), -6),
                $row['subject'] ?? '',
                $row['description'] ?? '',
                $row['unit_name'] ?? '',
                $fullName,
                $category,
            ]))),
        ];
    }

    private function buildTotals(array $tickets, array $activeTickets): array
    {
        $pending = 0;
        $inProgress = 0;
        $resolved = 0;
        $overduePending = 0;
        $needsAttention = 0;
        $createdToday = 0;
        $unassigned = 0;
        $staffCountMap = [];

        $today = date('Y-m-d');
        foreach ($tickets as $ticket) {
            if ($ticket['status'] === 'open') {
                $pending++;
            }
            if ($ticket['status'] === 'in_progress') {
                $inProgress++;
            }
            if (in_array($ticket['status'], ['resolved', 'closed'], true)) {
                $resolved++;
            }
            if ($ticket['is_overdue']) {
                $overduePending++;
            }
            if ($ticket['is_attention']) {
                $needsAttention++;
            }
            if (date('Y-m-d', $ticket['created_ts']) === $today) {
                $createdToday++;
            }
        }

        foreach ($activeTickets as $ticket) {
            if (empty($ticket['assigned_to_id'])) {
                $unassigned++;
            } else {
                $staffCountMap[$ticket['assigned_to_type'] . '_' . $ticket['assigned_to_id']] = true;
            }
        }

        return [
            'open_total' => count($activeTickets),
            'pending' => $pending,
            'in_progress' => $inProgress,
            'resolved' => $resolved,
            'overdue_pending' => $overduePending,
            'needs_attention' => $needsAttention,
            'created_today' => $createdToday,
            'unassigned' => $unassigned,
            'staff_total' => count($staffCountMap),
        ];
    }

    private function buildAgingBreakdown(array $activeTickets): array
    {
        $now = time();
        $lt24 = 0;
        $between24And48 = 0;
        $gt48 = 0;

        foreach ($activeTickets as $ticket) {
            $ageHours = (int) floor(max(0, ($now - $ticket['created_ts']) / 3600));
            if ($ageHours < 24) {
                $lt24++;
            } elseif ($ageHours <= 48) {
                $between24And48++;
            } else {
                $gt48++;
            }
        }

        $total = max(1, count($activeTickets));

        return [
            [
                'label' => '< 24 horas',
                'count' => $lt24,
                'pct' => (int) round(($lt24 / $total) * 100),
                'bar' => 'bg-success',
            ],
            [
                'label' => '24-48 horas',
                'count' => $between24And48,
                'pct' => (int) round(($between24And48 / $total) * 100),
                'bar' => 'bg-warning',
            ],
            [
                'label' => '> 48 horas',
                'count' => $gt48,
                'pct' => (int) round(($gt48 / $total) * 100),
                'bar' => 'bg-danger',
            ],
        ];
    }

    private function buildAlerts(array $totals): array
    {
        $alerts = [];

        if ($totals['overdue_pending'] > 0) {
            $alerts[] = [
                'icon' => 'bi-exclamation-triangle',
                'class' => 'text-danger',
                'text' => $totals['overdue_pending'] . ' ticket(s) vencido(s) requieren atencion',
            ];
        }

        if ($totals['unassigned'] > 0) {
            $alerts[] = [
                'icon' => 'bi-person-exclamation',
                'class' => 'text-warning',
                'text' => $totals['unassigned'] . ' ticket(s) sin asignar necesitan atencion',
            ];
        }

        if (empty($alerts)) {
            $alerts[] = [
                'icon' => 'bi-check-circle',
                'class' => 'text-success',
                'text' => 'No hay alertas activas en este momento',
            ];
        }

        return $alerts;
    }

    private function resolvePeriod(string $period): array
    {
        $today = new \DateTimeImmutable('today');

        if ($period === 'semana') {
            $start = $today->modify('monday this week');
            $end = $today->setTime(23, 59, 59);
            return [$start->getTimestamp(), $end->getTimestamp(), 7];
        }

        if ($period === 'trimestre') {
            $month = (int) $today->format('n');
            $quarterMonth = (int) (floor(($month - 1) / 3) * 3) + 1;
            $start = $today->setDate((int) $today->format('Y'), $quarterMonth, 1)->setTime(0, 0, 0);
            $end = $today->setTime(23, 59, 59);
            return [$start->getTimestamp(), $end->getTimestamp(), 12];
        }

        if ($period === 'anio') {
            $start = $today->setDate((int) $today->format('Y'), 1, 1)->setTime(0, 0, 0);
            $end = $today->setTime(23, 59, 59);
            return [$start->getTimestamp(), $end->getTimestamp(), 12];
        }

        $start = $today->modify('first day of this month')->setTime(0, 0, 0);
        $end = $today->setTime(23, 59, 59);
        return [$start->getTimestamp(), $end->getTimestamp(), 14];
    }

    private function buildTrendSeries(array $tickets, int $startTs, int $endTs, int $bucketCount): array
    {
        $bucketCount = max(3, $bucketCount);
        $range = max(1, ($endTs - $startTs) + 1);
        $created = array_fill(0, $bucketCount, 0);
        $resolved = array_fill(0, $bucketCount, 0);
        $labels = [];

        for ($i = 0; $i < $bucketCount; $i++) {
            $bucketTs = (int) ($startTs + (($range / $bucketCount) * $i));
            $labels[] = date('M d', $bucketTs);
        }

        foreach ($tickets as $ticket) {
            if ($ticket['created_ts'] >= $startTs && $ticket['created_ts'] <= $endTs) {
                $idx = (int) floor((($ticket['created_ts'] - $startTs) / $range) * $bucketCount);
                $idx = max(0, min($bucketCount - 1, $idx));
                $created[$idx]++;
            }

            if (in_array($ticket['status'], ['resolved', 'closed'], true) && $ticket['updated_ts'] >= $startTs && $ticket['updated_ts'] <= $endTs) {
                $idx = (int) floor((($ticket['updated_ts'] - $startTs) / $range) * $bucketCount);
                $idx = max(0, min($bucketCount - 1, $idx));
                $resolved[$idx]++;
            }
        }

        $points = [];
        $backlog = 0;
        $maxVolume = 1;
        $maxBacklog = 1;

        for ($i = 0; $i < $bucketCount; $i++) {
            $backlog += ($created[$i] - $resolved[$i]);
            $maxVolume = max($maxVolume, $created[$i], $resolved[$i]);
            $maxBacklog = max($maxBacklog, abs($backlog));

            $points[] = [
                'label' => $labels[$i],
                'created' => $created[$i],
                'resolved' => $resolved[$i],
                'backlog' => $backlog,
            ];
        }

        return [
            'points' => $points,
            'max_volume' => $maxVolume,
            'max_backlog' => $maxBacklog,
            'net_change' => $backlog,
            'created_total' => array_sum($created),
            'resolved_total' => array_sum($resolved),
        ];
    }

    private function buildCategoryDistribution(array $tickets): array
    {
        $counts = [];
        foreach ($tickets as $ticket) {
            $cat = $ticket['category'] ?: 'Otros';
            if (!isset($counts[$cat])) {
                $counts[$cat] = 0;
            }
            $counts[$cat]++;
        }

        arsort($counts);
        $total = max(1, array_sum($counts));
        $result = [];

        foreach ($counts as $category => $count) {
            $result[] = [
                'category' => $category,
                'count' => $count,
                'pct' => (int) round(($count / $total) * 100),
            ];
        }

        return $result;
    }

    private function buildResolutionStats(array $periodTickets): array
    {
        $resolvedCount = 0;
        $resolutionSeconds = 0;

        foreach ($periodTickets as $ticket) {
            if (in_array($ticket['status'], ['resolved', 'closed'], true)) {
                $resolvedCount++;
                $resolutionSeconds += max(0, $ticket['updated_ts'] - $ticket['created_ts']);
            }
        }

        $createdCount = count($periodTickets);
        $avgSeconds = $resolvedCount > 0 ? (int) floor($resolutionSeconds / $resolvedCount) : 0;
        $rate = $createdCount > 0 ? (int) round(($resolvedCount / $createdCount) * 100) : 0;

        return [
            'total_reports' => $createdCount,
            'open_reports' => count(array_filter($periodTickets, static fn(array $ticket): bool => $ticket['bucket'] === 'active')),
            'resolved_reports' => $resolvedCount,
            'avg_resolution' => $this->toVerboseDuration($avgSeconds),
            'resolution_rate' => $rate,
        ];
    }

    private function buildStaffMetrics(array $periodTickets, array $assignees): array
    {
        $staffStats = [];

        foreach ($assignees['admins'] as $a) {
            $key = 'user_' . $a['id'];
            $staffStats[$key] = [
                'name' => trim($a['first_name'] . ' ' . $a['last_name']),
                'assigned' => 0,
                'resolved' => 0,
                'resolution_seconds' => 0,
            ];
        }
        foreach ($assignees['staff'] as $s) {
            $key = 'staff_' . $s['id'];
            $staffStats[$key] = [
                'name' => trim($s['first_name'] . ' ' . $s['last_name']),
                'assigned' => 0,
                'resolved' => 0,
                'resolution_seconds' => 0,
            ];
        }

        foreach ($periodTickets as $ticket) {
            if (!empty($ticket['assigned_to_type']) && !empty($ticket['assigned_to_id'])) {
                $key = $ticket['assigned_to_type'] . '_' . $ticket['assigned_to_id'];
                if (isset($staffStats[$key])) {
                    $staffStats[$key]['assigned']++;
                    
                    if (in_array($ticket['status'], ['resolved', 'closed'], true)) {
                        $staffStats[$key]['resolved']++;
                        $staffStats[$key]['resolution_seconds'] += max(0, $ticket['updated_ts'] - $ticket['created_ts']);
                    }
                }
            }
        }

        $finalStats = [];
        foreach ($staffStats as $stat) {
            if ($stat['assigned'] > 0 || $stat['resolved'] > 0) {
                $avgSeconds = $stat['resolved'] > 0 ? (int) floor($stat['resolution_seconds'] / $stat['resolved']) : 0;
                $stat['avg_resolution'] = $this->toVerboseDuration($avgSeconds);
                $finalStats[] = $stat;
            }
        }
        
        usort($finalStats, fn($a, $b) => $b['resolved'] <=> $a['resolved']);

        return $finalStats;
    }

    private function resolveCategory(string $subject, string $description): string
    {
        $text = strtolower(trim($subject . ' ' . $description));

        if ($text === '') {
            return 'Otros';
        }

        if (str_contains($text, 'ruido')) {
            return 'Ruido';
        }

        if (str_contains($text, 'agua') || str_contains($text, 'elevador') || str_contains($text, 'luz') || str_contains($text, 'fuga') || str_contains($text, 'mantenimiento')) {
            return 'Mantenimiento';
        }

        if (str_contains($text, 'amenidad') || str_contains($text, 'reserva') || str_contains($text, 'gimnasio') || str_contains($text, 'alberca')) {
            return 'Amenidades';
        }

        if (str_contains($text, 'servicio') || str_contains($text, 'limpieza') || str_contains($text, 'vigilancia')) {
            return 'Servicios';
        }

        return 'Otros';
    }

    private function extractInitials(string $fullName): string
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];
        $first = $parts[0] ?? '';
        $second = $parts[1] ?? '';

        $initials = '';
        if ($first !== '') {
            $initials .= strtoupper(substr($first, 0, 1));
        }
        if ($second !== '') {
            $initials .= strtoupper(substr($second, 0, 1));
        }

        return $initials !== '' ? $initials : 'R';
    }

    private function toShortDuration(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $days = (int) floor($seconds / 86400);
        $hours = (int) floor(($seconds % 86400) / 3600);

        if ($days > 0) {
            return $hours > 0 ? $days . 'd ' . $hours . 'h' : $days . 'd';
        }

        $minutes = (int) floor(($seconds % 3600) / 60);
        if ($hours > 0) {
            return $hours . 'h';
        }
        if ($minutes > 0) {
            return $minutes . 'm';
        }

        return '0m';
    }

    private function toVerboseDuration(int $seconds): string
    {
        $seconds = max(0, $seconds);
        $days = (int) floor($seconds / 86400);
        $hours = (int) floor(($seconds % 86400) / 3600);

        if ($days > 0) {
            return $days . 'd ' . $hours . 'h';
        }

        if ($hours > 0) {
            return $hours . 'h';
        }

        $minutes = (int) floor(($seconds % 3600) / 60);
        return $minutes . 'm';
    }

    /**
     * Sirve imágenes y videos adjuntos en los tickets desde la carpeta writable
     */
    public function serveTicketMedia($fileName)
    {
        // Prevenir directory traversal
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/tickets/' . $fileName;

        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        // Handle MP4 mime explicitly if not detected properly by mime_content_type
        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'mp4') {
            $mimeType = 'video/mp4';
        }
        
        $filesize = filesize($filePath);
        
        // Return stream for large video files
        if (str_starts_with($mimeType, 'video/')) {
            $this->response->setHeader('Accept-Ranges', 'bytes');
            $this->response->setHeader('Content-Length', (string)$filesize);
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    // ─── TICKET NOTIFICATION HELPERS ─────────────────

    /**
     * Notificar al residente dueño de un ticket
     */
    private function notifyTicketResident(int $condoId, int $residentUserId, string $title, string $body, array $extraData = []): void
    {
        try {
            $db = \Config\Database::connect();
            $now = date('Y-m-d H:i:s');
            $db->table('notifications')->insert([
                'condominium_id' => $condoId,
                'user_id'        => $residentUserId,
                'type'           => 'ticket',
                'title'          => $title,
                'body'           => $body,
                'data'           => json_encode($extraData),
                'read_at'        => null,
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        } catch (\Throwable $e) {
            log_message('error', '[TICKET_NOTIF] notifyTicketResident failed: ' . $e->getMessage());
        }
    }

    /**
     * Get admin display name
     */
    private function getAdminName(int $userId): string
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->select('first_name, last_name')->where('id', $userId)->get()->getRowArray();
        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        return $name ?: 'Administrador';
    }
}
