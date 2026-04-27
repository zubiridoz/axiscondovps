<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\TicketModel;
use App\Models\Tenant\TicketCommentModel;
use App\Models\Tenant\ResidentModel;

/**
 * TicketApiController
 * 
 * REST API for Resident Tickets (Reportes).
 * Reuses existing TicketController logic without duplicating it.
 * Enforces unit_id isolation for multi-unit residents.
 */
class TicketApiController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $message
        ])->setStatusCode($status);
    }

    /**
     * Resolve current resident's unit_id for data isolation
     */
    private function resolveResident(int $userId): ?array
    {
        $residentModel = new ResidentModel();
        return $residentModel->where('user_id', $userId)->first();
    }

    /**
     * Check if user is an admin
     */
    private function isAdmin(int $userId): bool
    {
        $ucrModel = new \App\Models\Tenant\UserCondominiumRoleModel();
        $role = $ucrModel->where('user_id', $userId)
                         ->where('role_id', 2) // Admin role
                         ->first();
        return $role !== null;
    }

    /**
     * GET /api/v1/resident/tickets
     * Active tickets (status != resolved/closed) for current unit
     */
    public function active()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $resident = $this->resolveResident($userId);
        $currentUnitId = $resident['unit_id'] ?? null;
        $condoId = $resident['condominium_id'] ?? null;

        $isAdmin = $this->isAdmin($userId);

        $ticketModel = new TicketModel();
        $builder = $ticketModel
            ->select('tickets.*, units.unit_number as unit_name, u.first_name, u.last_name')
            ->join('units', 'units.id = tickets.unit_id', 'left')
            ->join('users u', 'u.id = tickets.reported_by', 'left')
            ->whereNotIn('tickets.status', ['resolved', 'closed']);

        if (!$isAdmin) {
            $builder->where('tickets.reported_by', $userId);
            if ($currentUnitId) {
                $builder->where('tickets.unit_id', $currentUnitId);
            }
        }

        $rows = $builder->orderBy('tickets.created_at', 'DESC')->findAll();

        $tickets = array_map(fn($row) => $this->normalizeTicketApi($row), $rows);

        return $this->respondSuccess(['tickets' => $tickets]);
    }

    /**
     * GET /api/v1/resident/tickets/resolved
     * Resolved/closed tickets for current unit
     */
    public function resolved()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $resident = $this->resolveResident($userId);
        $currentUnitId = $resident['unit_id'] ?? null;

        $isAdmin = $this->isAdmin($userId);

        $ticketModel = new TicketModel();
        $builder = $ticketModel
            ->select('tickets.*, units.unit_number as unit_name, u.first_name, u.last_name')
            ->join('units', 'units.id = tickets.unit_id', 'left')
            ->join('users u', 'u.id = tickets.reported_by', 'left')
            ->whereIn('tickets.status', ['resolved', 'closed']);

        if (!$isAdmin) {
            $builder->where('tickets.reported_by', $userId);
            if ($currentUnitId) {
                $builder->where('tickets.unit_id', $currentUnitId);
            }
        }

        $rows = $builder->orderBy('tickets.updated_at', 'DESC')->findAll();

        $tickets = array_map(fn($row) => $this->normalizeTicketApi($row), $rows);

        return $this->respondSuccess(['tickets' => $tickets]);
    }

    /**
     * GET /api/v1/resident/tickets/{id}
     * Detail of a specific ticket
     */
    public function detail($id = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);
        if (!$id) return $this->respondError('ID requerido', 400);

        $isAdmin = $this->isAdmin($userId);

        $ticketModel = new TicketModel();
        $builder = $ticketModel
            ->select('tickets.*, units.unit_number as unit_name, u.first_name, u.last_name')
            ->join('units', 'units.id = tickets.unit_id', 'left')
            ->join('users u', 'u.id = tickets.reported_by', 'left')
            ->where('tickets.id', $id);

        if (!$isAdmin) {
            $builder->where('tickets.reported_by', $userId);
        }
        
        $row = $builder->first();

        if (!$row) return $this->respondError('Ticket no encontrado', 404);

        return $this->respondSuccess(['ticket' => $this->normalizeTicketApi($row)]);
    }

    /**
     * POST /api/v1/resident/tickets
     * Create a new ticket — reuses upload logic from admin TicketController
     */
    public function create()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $resident = $this->resolveResident($userId);
        if (!$resident) return $this->respondError('Residente no encontrado', 404);

        $condoId = $resident['condominium_id'] ?? null;
        $unitId = $resident['unit_id'] ?? null;

        $description = trim((string) $this->request->getPost('description'));
        $category = $this->request->getPost('category') ?: 'Otro';
        $priority = $this->request->getPost('priority') ?: 'medium';

        if (empty($description)) {
            return $this->respondError('La descripción es requerida');
        }

        $subject = mb_strlen($description) > 50 ? mb_substr($description, 0, 47) . '...' : $description;

        // Handle media uploads — same logic as admin TicketController
        $mediaUrls = [];
        $files = $this->request->getFileMultiple('media');
        if ($files) {
            $uploadDir = WRITEPATH . 'uploads/tickets';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            foreach ($files as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $ext = $file->getExtension();
                    $newName = 'tk_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    $file->move($uploadDir, $newName);
                    $mediaUrls[] = 'writable/uploads/tickets/' . $newName;
                    if (count($mediaUrls) >= 7) break;
                }
            }
        }

        $data = [
            'condominium_id' => $condoId,
            'unit_id'        => $unitId,
            'reported_by'    => $userId,
            'ticket_hash'    => substr(bin2hex(random_bytes(16)), 0, 24),
            'subject'        => $subject,
            'description'    => $description,
            'category'       => $category,
            'priority'       => $priority,
            'media_urls'     => !empty($mediaUrls) ? json_encode($mediaUrls) : null,
            'status'         => 'open',
        ];

        $ticketModel = new TicketModel();
        $ticketId = $ticketModel->insert($data);

        if (!$ticketId) {
            return $this->respondError('Error al crear reporte: ' . implode(', ', $ticketModel->errors()));
        }

        // ── Notificar a los administradores del condominio ──
        $this->notifyAdmins(
            $condoId,
            '🔧 Nuevo Reporte',
            $this->getResidentName($userId) . ' reportó: ' . mb_substr($description, 0, 100),
            ['ticket_id' => $ticketId, 'type' => 'ticket_created']
        );

        return $this->respondSuccess([
            'id'      => $ticketId,
            'hash'    => $data['ticket_hash'],
            'message' => 'Reporte creado exitosamente'
        ]);
    }

    /**
     * GET /api/v1/resident/tickets/{id}/messages
     * Get conversation messages for a ticket
     */
    public function getMessages($ticketId = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);
        if (!$ticketId) return $this->respondError('ID requerido', 400);

        $isAdmin = $this->isAdmin($userId);

        // Verify ownership
        $ticketModel = new TicketModel();
        $builder = $ticketModel->where('id', $ticketId);
        if (!$isAdmin) {
            $builder->where('reported_by', $userId);
        }
        $ticket = $builder->first();
        if (!$ticket) return $this->respondError('Ticket no encontrado', 404);

        $condoId = $ticket['condominium_id'];
        $db = \Config\Database::connect();

        $rows = $db->table('ticket_comments tc')
            ->select('tc.*, u.first_name, u.last_name')
            ->join('users u', 'u.id = tc.user_id', 'left')
            ->where('tc.ticket_id', $ticketId)
            ->where('tc.condominium_id', $condoId)
            ->where('tc.deleted_at IS NULL')
            ->where('tc.type', 'reply') // Only show replies, not internal notes
            ->orderBy('tc.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $messages = [];
        foreach ($rows as $row) {
            $name = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
            if ($name === '') $name = 'Administrador';

            $mediaUrls = $row['media_urls'] ? json_decode($row['media_urls'], true) : [];
            $mediaOut = [];
            foreach ($mediaUrls as $mUrl) {
                $mediaOut[] = basename($mUrl);
            }

            $createdAt = strtotime($row['created_at'] ?? 'now');
            $timeLabel = $this->timeAgo($createdAt);

            $messages[] = [
                'id'         => (int) $row['id'],
                'user_id'    => (int) $row['user_id'],
                'name'       => $name,
                'is_mine'    => ((int) $row['user_id'] === (int) $userId),
                'message'    => $row['message'],
                'media_urls' => $mediaOut,
                'time_label' => $timeLabel,
                'created_at' => $row['created_at'],
            ];
        }

        return $this->respondSuccess(['messages' => $messages]);
    }

    /**
     * POST /api/v1/resident/tickets/{id}/message
     * Send a message in ticket conversation
     */
    public function sendMessage($ticketId = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);
        if (!$ticketId) return $this->respondError('ID requerido', 400);

        $isAdmin = $this->isAdmin($userId);

        // Verify ownership
        $ticketModel = new TicketModel();
        $builder = $ticketModel->where('id', $ticketId);
        if (!$isAdmin) {
            $builder->where('reported_by', $userId);
        }
        $ticket = $builder->first();
        if (!$ticket) return $this->respondError('Ticket no encontrado', 404);

        $message = trim((string) $this->request->getPost('message'));

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

        if ($message === '' && empty($mediaUrls)) {
            return $this->respondError('El mensaje no puede estar vacío');
        }

        $commentModel = new TicketCommentModel();
        $data = [
            'ticket_id'      => $ticketId,
            'condominium_id' => $ticket['condominium_id'],
            'user_id'        => $userId,
            'message'        => $message,
            'type'           => 'reply',
            'media_urls'     => !empty($mediaUrls) ? json_encode($mediaUrls) : null,
        ];

        $commentId = $commentModel->insert($data);
        if (!$commentId) {
            return $this->respondError('Error al enviar mensaje');
        }

        // ── Auto-transition: open → in_progress when admin replies ──
        if ($isAdmin && $ticket['status'] === 'open') {
            $ticketModel->update($ticketId, ['status' => 'in_progress']);
        }

        // ── Notificaciones ──
        if ($isAdmin && $ticket['reported_by'] != $userId) {
            $this->notifyResident(
                $ticket['condominium_id'],
                $ticket['reported_by'],
                '💬 Nuevo comentario en tu reporte',
                'La administración comentó: ' . mb_substr($message ?: '📷 Adjunto', 0, 100),
                ['ticket_id' => $ticketId, 'type' => 'ticket_admin_comment']
            );
        } else {
            $this->notifyAdmins(
                $ticket['condominium_id'],
                '💬 Nuevo comentario en reporte',
                $this->getResidentName($userId) . ': ' . mb_substr($message ?: '📷 Adjunto', 0, 100),
                ['ticket_id' => $ticketId, 'type' => 'ticket_resident_comment']
            );
        }

        return $this->respondSuccess([
            'id'      => $commentId,
            'message' => 'Mensaje enviado'
        ]);
    }

    /**
     * POST /api/v1/resident/tickets/{id}/resolve
     * Mark a ticket as resolved
     */
    public function resolve($ticketId = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);
        if (!$ticketId) return $this->respondError('ID requerido', 400);

        $isAdmin = $this->isAdmin($userId);

        $ticketModel = new TicketModel();
        $builder = $ticketModel->where('id', $ticketId);
        if (!$isAdmin) {
            $builder->where('reported_by', $userId);
        }
        $ticket = $builder->first();
        if (!$ticket) return $this->respondError('Ticket no encontrado', 404);

        $ticketModel->update($ticketId, ['status' => 'resolved']);

        if ($isAdmin && $ticket['reported_by'] != $userId) {
            $this->notifyResident(
                $ticket['condominium_id'],
                $ticket['reported_by'],
                '✅ Reporte Resuelto',
                'La administración ha marcado tu reporte como resuelto.',
                ['ticket_id' => $ticketId, 'type' => 'ticket_resolved']
            );
        }

        return $this->respondSuccess(['message' => 'Reporte marcado como resuelto']);
    }

    /**
     * Serve media files for tickets via API
     */
    public function serveMedia($fileName = null)
    {
        if (!$fileName) return $this->respondError('Archivo no especificado', 400);
        
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/tickets/' . $fileName;

        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        if (strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) === 'mp4') {
            $mimeType = 'video/mp4';
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    // ─── PRIVATE HELPERS ───────────────────────────

    private function normalizeTicketApi(array $row): array
    {
        $createdTs = strtotime($row['created_at'] ?? '') ?: time();
        $updatedTs = strtotime($row['updated_at'] ?? '') ?: $createdTs;
        $status = $row['status'] ?? 'open';

        $statusLabels = [
            'open'        => 'Pendiente',
            'in_progress' => 'En progreso',
            'resolved'    => 'Resuelto',
            'closed'      => 'Cerrado',
        ];

        $priorityLabels = ['low' => 'Bajo', 'medium' => 'Medio', 'high' => 'Alto', 'critical' => 'Crítico'];
        $priority = strtolower($row['priority'] ?? 'medium');

        $fullName = trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? ''));
        if ($fullName === '') $fullName = 'Residente';

        $mediaUrlsStr = $row['media_urls'] ?? null;
        $mediaUrls = $mediaUrlsStr ? json_decode($mediaUrlsStr, true) : [];
        $mediaOut = [];
        foreach ($mediaUrls as $mUrl) {
            $mediaOut[] = basename($mUrl);
        }

        $shortHash = substr($row['ticket_hash'] ?? ('000' . ($row['id'] ?? 0)), -6);

        return [
            'id'             => (int) ($row['id'] ?? 0),
            'hash'           => $row['ticket_hash'] ?? '',
            'short_hash'     => strtoupper($shortHash),
            'subject'        => trim($row['subject'] ?? 'Sin asunto'),
            'description'    => trim($row['description'] ?? ''),
            'unit_name'      => trim($row['unit_name'] ?? 'Sin unidad'),
            'reporter'       => $fullName,
            'status'         => $status,
            'status_label'   => $statusLabels[$status] ?? 'Desconocido',
            'category'       => $row['category'] ?? 'Otro',
            'priority'       => $priority,
            'priority_label' => $priorityLabels[$priority] ?? 'Medio',
            'media_urls'     => $mediaOut,
            'media_count'    => count($mediaOut),
            'created_at'     => $row['created_at'] ?? '',
            'updated_at'     => $row['updated_at'] ?? '',
            'time_ago'       => $this->timeAgo($createdTs),
            'updated_ago'    => $this->timeAgo($updatedTs),
        ];
    }

    private function timeAgo(int $timestamp): string
    {
        $diff = time() - $timestamp;
        if ($diff < 60) return 'hace <1 min';
        if ($diff < 3600) return 'hace ' . (int) floor($diff / 60) . ' min';
        if ($diff < 86400) return 'hace ' . (int) floor($diff / 3600) . 'h';
        if ($diff < 172800) return 'hace 1 día';
        if ($diff < 604800) return 'hace ' . (int) floor($diff / 86400) . ' días';
        return date('d/m/Y', $timestamp);
    }

    // ─── NOTIFICATION HELPERS ──────────────────────────

    /**
     * Notificar a todos los admins de un condominio
     */
    private function notifyAdmins(int $condoId, string $title, string $body, array $extraData = []): void
    {
        try {
            $db = \Config\Database::connect();
            $admins = $db->table('users')
                ->distinct()
                ->select('users.id')
                ->join('user_condominium_roles ucr', 'ucr.user_id = users.id')
                ->join('roles r', 'r.id = ucr.role_id')
                ->groupStart()
                    ->where('ucr.condominium_id', $condoId)
                    ->orWhere('ucr.condominium_id IS NULL')
                ->groupEnd()
                ->whereIn('r.name', ['ADMIN', 'SUPER_ADMIN'])
                ->get()
                ->getResultArray();

            $now = date('Y-m-d H:i:s');
            foreach ($admins as $admin) {
                $db->table('notifications')->insert([
                    'condominium_id' => $condoId,
                    'user_id'        => $admin['id'],
                    'type'           => 'ticket',
                    'title'          => $title,
                    'body'           => $body,
                    'data'           => json_encode($extraData),
                    'read_at'        => null,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }
        } catch (\Throwable $e) {
            log_message('error', '[TICKET_NOTIF] Admin notify failed: ' . $e->getMessage());
        }
    }

    /**
     * Notificar al residente dueño del ticket
     */
    private function notifyResident(int $condoId, int $residentUserId, string $title, string $body, array $extraData = []): void
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
            log_message('error', '[TICKET_NOTIF] Resident notify failed: ' . $e->getMessage());
        }
    }

    /**
     * Get resident display name
     */
    private function getResidentName(int $userId): string
    {
        $db = \Config\Database::connect();
        $user = $db->table('users')->select('first_name, last_name')->where('id', $userId)->get()->getRowArray();
        $name = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        return $name ?: 'Residente';
    }
}
