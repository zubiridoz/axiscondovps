<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\ContentReportModel;
use App\Models\Tenant\BlockedUserModel;

/**
 * ModerationController (API V1)
 *
 * Endpoints para cumplir Apple App Store Guideline 1.2:
 *  - Reportar contenido ofensivo
 *  - Bloquear / desbloquear usuarios
 *  - Listar usuarios bloqueados
 */
class ModerationController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])->setStatusCode($status);
    }

    // ─────────────────────────────────────────────
    // REPORTAR CONTENIDO
    // ─────────────────────────────────────────────

    /**
     * POST /api/v1/moderation/report
     *
     * Body JSON:
     *  - announcement_id (int|null)
     *  - comment_id      (int|null)
     *  - reported_user_id (int)
     *  - reason           (string) spam|harassment|offensive|misinformation|other
     *  - description      (string|null)
     */
    public function report()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        if (!is_array($json)) {
            $json = $this->request->getPost();
        }

        $reason = trim((string)($json['reason'] ?? ''));
        $validReasons = ['spam', 'harassment', 'offensive', 'misinformation', 'other'];
        if (!in_array($reason, $validReasons)) {
            return $this->respondError('Motivo de reporte inválido');
        }

        $announcementId  = !empty($json['announcement_id']) ? (int)$json['announcement_id'] : null;
        $commentId       = !empty($json['comment_id']) ? (int)$json['comment_id'] : null;
        $reportedUserId  = !empty($json['reported_user_id']) ? (int)$json['reported_user_id'] : null;

        if (!$announcementId && !$commentId) {
            return $this->respondError('Debe indicar una publicación o comentario a reportar');
        }

        // Evitar auto-reportes
        if ($reportedUserId && (int)$reportedUserId === (int)$userId) {
            return $this->respondError('No puedes reportar tu propio contenido');
        }

        // Verificar duplicados (mismo usuario, mismo target, misma razón)
        $checkModel = new ContentReportModel();
        $checkModel->where('reporter_user_id', $userId)
                   ->where('reason', $reason);

        if ($announcementId) {
            $checkModel->where('announcement_id', $announcementId);
        }
        if ($commentId) {
            $checkModel->where('comment_id', $commentId);
        }

        if ($checkModel->countAllResults() > 0) {
            // Silently accept duplicate — no error to user
            return $this->respondSuccess(['message' => 'Reporte enviado. Gracias por ayudar a mantener la comunidad segura.']);
        }

        // Usar instancia FRESCA para el insert
        $insertModel = new ContentReportModel();
        $insertData = [
            'reporter_user_id' => $userId,
            'reported_user_id' => $reportedUserId,
            'announcement_id'  => $announcementId,
            'comment_id'       => $commentId,
            'reason'           => $reason,
            'description'      => trim((string)($json['description'] ?? '')) ?: null,
            'status'           => 'pending',
        ];

        $inserted = $insertModel->insert($insertData);
        if (!$inserted) {
            log_message('error', '[MODERATION] Insert failed. Errors: ' . json_encode($insertModel->errors()) . ' Data: ' . json_encode($insertData));
            return $this->respondError('No se pudo guardar el reporte. Intenta de nuevo.', 500);
        }
        log_message('info', '[MODERATION] Report saved ID=' . $inserted . ' by user=' . $userId);

        // Notificar administradores del condominio
        $this->notifyAdminsOfReport($userId, $reason, $announcementId, $commentId);

        return $this->respondSuccess(['message' => 'Reporte enviado. Gracias por ayudar a mantener la comunidad segura.']);
    }

    // ─────────────────────────────────────────────
    // BLOQUEAR USUARIO
    // ─────────────────────────────────────────────

    /**
     * POST /api/v1/moderation/block
     *
     * Body JSON:
     *  - blocked_user_id (int)
     */
    public function block()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        if (!is_array($json)) {
            $json = $this->request->getPost();
        }

        $blockedUserId = (int)($json['blocked_user_id'] ?? 0);
        if (!$blockedUserId) {
            return $this->respondError('ID de usuario a bloquear no proporcionado');
        }

        if ($blockedUserId === (int)$userId) {
            return $this->respondError('No puedes bloquearte a ti mismo');
        }

        $blockModel = new BlockedUserModel();

        // Verificar si ya existe
        $existing = $blockModel
            ->where('user_id', $userId)
            ->where('blocked_user_id', $blockedUserId)
            ->first();

        if ($existing) {
            return $this->respondSuccess(['message' => 'Usuario ya bloqueado']);
        }

        $blockModel->insert([
            'user_id'         => $userId,
            'blocked_user_id' => $blockedUserId,
        ]);

        return $this->respondSuccess(['message' => 'Usuario bloqueado. Ya no verás su contenido.']);
    }

    // ─────────────────────────────────────────────
    // DESBLOQUEAR USUARIO
    // ─────────────────────────────────────────────

    /**
     * POST /api/v1/moderation/unblock
     *
     * Body JSON:
     *  - blocked_user_id (int)
     */
    public function unblock()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        if (!is_array($json)) {
            $json = $this->request->getPost();
        }

        $blockedUserId = (int)($json['blocked_user_id'] ?? 0);
        if (!$blockedUserId) {
            return $this->respondError('ID de usuario no proporcionado');
        }

        $blockModel = new BlockedUserModel();
        $blockModel
            ->where('user_id', $userId)
            ->where('blocked_user_id', $blockedUserId)
            ->delete();

        return $this->respondSuccess(['message' => 'Usuario desbloqueado']);
    }

    // ─────────────────────────────────────────────
    // LISTAR USUARIOS BLOQUEADOS
    // ─────────────────────────────────────────────

    /**
     * GET /api/v1/moderation/blocked-users
     */
    public function blockedUsers()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $blockModel = new BlockedUserModel();
        $db = \Config\Database::connect();

        $rows = $db->table('blocked_users bu')
            ->select('bu.id, bu.blocked_user_id, u.first_name, u.last_name, u.avatar, bu.created_at')
            ->join('users u', 'u.id = bu.blocked_user_id', 'left')
            ->where('bu.user_id', $userId)
            ->where('bu.condominium_id', \App\Services\TenantService::getInstance()->getTenantId())
            ->orderBy('bu.created_at', 'DESC')
            ->get()->getResultArray();

        return $this->respondSuccess(['blocked_users' => $rows]);
    }

    // ─────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────

    /**
     * Notifica a los administradores sobre un nuevo reporte de contenido.
     */
    private function notifyAdminsOfReport(int $reporterUserId, string $reason, ?int $announcementId, ?int $commentId): void
    {
        try {
            $db = \Config\Database::connect();
            $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

            $user = $db->table('users')->select('first_name, last_name')->where('id', $reporterUserId)->get()->getRow();
            $userName = $user ? trim($user->first_name . ' ' . $user->last_name) : 'Un residente';

            $reasonLabels = [
                'spam'            => 'Spam',
                'harassment'      => 'Acoso',
                'offensive'       => 'Contenido ofensivo',
                'misinformation'  => 'Información falsa',
                'other'           => 'Otro',
            ];
            $reasonLabel = $reasonLabels[$reason] ?? $reason;

            $target = $announcementId ? 'una publicación' : 'un comentario';
            $body = "{$userName} reportó {$target} por: {$reasonLabel}";

            $admins = $db->table('user_condominium_roles ucr')
                ->select('ucr.user_id')
                ->join('roles r', 'r.id = ucr.role_id')
                ->where('ucr.condominium_id', $tenantId)
                ->whereIn('LOWER(r.name)', ['admin', 'super_admin', 'owner'])
                ->get()->getResultArray();

            foreach ($admins as $admin) {
                if ((int)$admin['user_id'] !== (int)$reporterUserId) {
                    \App\Models\Tenant\NotificationModel::notify(
                        $tenantId,
                        (int)$admin['user_id'],
                        'content_report',
                        '⚠️ Reporte de contenido',
                        $body,
                        [
                            'type'            => 'content_report',
                            'announcement_id' => (string)($announcementId ?? ''),
                            'comment_id'      => (string)($commentId ?? ''),
                            'click_action'    => 'FLUTTER_NOTIFICATION_CLICK',
                        ]
                    );
                }
            }
        } catch (\Throwable $e) {
            log_message('error', '[MODERATION] Error notifying admins: ' . $e->getMessage());
        }
    }
}
