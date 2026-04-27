<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\NotificationModel;

class NotificationController extends BaseController
{
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Respetar el contexto multi-tenant de la sesión actual
        $condominiumId = session()->get('current_condominium_id');
        if ($condominiumId) {
            \App\Services\TenantService::getInstance()->setTenantId((int)$condominiumId);
        } else {
            // Fallback (solo dev)
            $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
            if ($demoCondo) {
                \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
            }
        }
    }
    public function syncTemp()
    {
        $db = \Config\Database::connect();

        $payments = $db->table('payments p')
            ->select('p.*, r.user_id as resident_user_id, u.first_name, u.last_name, un.number as unit_number')
            ->join('residents r', 'p.unit_id = r.unit_id')
            ->join('users u', 'r.user_id = u.id')
            ->join('units un', 'p.unit_id = un.id')
            ->where('p.status', 'pending')
            ->get()->getResultArray();

        if (!empty($payments)) {
            $admins = $db->table('users')->where('role', 'admin')->get()->getResultArray();
            
            foreach ($payments as $row) {
                $condoId = $row['condominium_id'];
                $title = "Comprobante Subido";
                $body = $row['first_name'] . " " . $row['last_name'] . " subió un comprobante de pago para " . $row['unit_number'];
                
                foreach ($admins as $admin) {
                    $adminId = $admin['id'];
                    $check = $db->table('notifications')
                        ->where('condominium_id', $condoId)
                        ->where('user_id', $adminId)
                        ->where('title', $title)
                        ->where('body', $body)
                        ->countAllResults();
                        
                    if ($check == 0) {
                        \App\Models\Tenant\NotificationModel::notify($condoId, $adminId, 'payment_status', $title, $body);
                    }
                }
            }
        }
        return $this->response->setJSON(['status' => 'synced']);
    }

    private function getCommonData()
    {
        $userId = session()->get('user_id');
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();

        if (!$userId || !$condoId) {
            return null;
        }

        return ['user_id' => $userId, 'condo_id' => $condoId];
    }

    public function getLatest()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        $auth = $this->getCommonData();
        if (!$auth) return $this->response->setJSON(['status' => 'error', 'message' => 'No session']);

        $db = \Config\Database::connect();
        $notifs = $db->table('notifications')
            ->where('condominium_id', $auth['condo_id'])
            // Por ahora, como es backend para que vea los uploads y demás de todos los admins:
            // si usaramos user_id restringido el owner no vería lo que otro hace si las notificaciones no van a todos los admins.
            // Para el SaaS condominio usualmente se envían notificaciones a los admins. Pero aquí lo acotamos al config o user_id.
            ->where('user_id', $auth['user_id'])
            ->orderBy('created_at', 'DESC')
            ->limit(50)
            ->get()->getResultArray();

        $unreadCount = $db->table('notifications')
            ->where('condominium_id', $auth['condo_id'])
            ->where('user_id', $auth['user_id'])
            ->where('read_at IS NULL')
            ->countAllResults();

        // format
        $items = [];
        foreach ($notifs as $n) {
            $dataPayload = !empty($n['data']) ? json_decode($n['data'], true) : [];
            $actionUrl = isset($dataPayload['action_url']) ? base_url($dataPayload['action_url']) : null;
            
            if (!$actionUrl) {
                if ($n['type'] === 'poll_activity' || $n['type'] === 'poll_new' || $n['type'] === 'poll_finished') {
                    $actionUrl = base_url('admin/encuestas');
                } elseif ($n['type'] === 'payment_status') {
                    $actionUrl = base_url('admin/finanzas/panel');
                } elseif ($n['type'] === 'reservation' || $n['type'] === 'amenidad') {
                    $actionUrl = base_url('admin/amenidades/reservas');
                } elseif ($n['type'] === 'ticket') {
                    $actionUrl = base_url('admin/tickets');
                } elseif ($n['type'] === 'calendar_event_new' || $n['type'] === 'calendar_event') {
                    $actionUrl = base_url('admin/calendario');
                }
            }
            
            $items[] = [
                'id' => $n['id'],
                'type' => $n['type'],
                'title' => $n['title'],
                'body' => $n['body'],
                'read' => !empty($n['read_at']),
                'created_at' => $n['created_at'],
                'time_ago' => $this->timeAgo($n['created_at']),
                'action_url' => $actionUrl
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $items,
            'total_count' => count($items)
        ]);
    }

    public function markAllRead()
    {
        if (!$this->request->isAJAX()) return $this->response->setStatusCode(400);

        $auth = $this->getCommonData();
        if ($auth) {
            $db = \Config\Database::connect();
            $db->table('notifications')
                ->where('condominium_id', $auth['condo_id'])
                ->where('user_id', $auth['user_id'])
                ->where('read_at IS NULL')
                ->update(['read_at' => date('Y-m-d H:i:s')]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    private function timeAgo($datetime)
    {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'hace un momento';
        if ($diff < 3600) return 'hace ' . floor($diff / 60) . ' min';
        if ($diff < 86400) return 'hace ' . floor($diff / 3600) . ' hora' . (floor($diff / 3600) > 1 ? 's' : '');
        return 'hace ' . floor($diff / 86400) . ' día' . (floor($diff / 86400) > 1 ? 's' : '');
    }
}
