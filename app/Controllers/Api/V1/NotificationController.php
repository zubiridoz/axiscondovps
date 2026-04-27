<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class NotificationController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])->setStatusCode($status);
    }

    /**
     * GET /api/v1/resident/notifications
     * Lista las notificaciones del usuario autenticado.
     */
    public function index()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $db = \Config\Database::connect();
        $notifications = $db->table('notifications')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $unreadCount = $db->table('notifications')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->where('read_at IS NULL')
            ->countAllResults();

        $items = [];
        foreach ($notifications as $n) {
            $items[] = [
                'id'         => (int) $n['id'],
                'type'       => $n['type'],
                'title'      => $n['title'],
                'body'       => $n['body'],
                'data'       => $n['data'] ? json_decode($n['data'], true) : null,
                'read'       => !empty($n['read_at']),
                'read_at'    => $n['read_at'],
                'created_at' => $n['created_at'],
            ];
        }

        return $this->respondSuccess([
            'notifications' => $items,
            'unread_count'  => $unreadCount,
        ]);
    }

    /**
     * POST /api/v1/resident/notifications/{id}/read
     * Marca una notificación como leída.
     */
    public function markRead($id = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->where('read_at IS NULL')
            ->update(['read_at' => date('Y-m-d H:i:s')]);

        return $this->respondSuccess(['message' => 'Notificación marcada como leída']);
    }

    /**
     * POST /api/v1/resident/notifications/read-all
     * Marca todas las notificaciones como leídas.
     */
    public function markAllRead()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $db = \Config\Database::connect();
        $db->table('notifications')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->where('read_at IS NULL')
            ->update(['read_at' => date('Y-m-d H:i:s')]);

        return $this->respondSuccess(['message' => 'Todas las notificaciones marcadas como leídas']);
    }
}
