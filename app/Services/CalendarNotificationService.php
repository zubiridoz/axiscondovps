<?php

namespace App\Services;

use App\Models\Tenant\NotificationModel;
use App\Models\Tenant\UserCondominiumRoleModel;
use App\Models\Core\UserModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\UnitModel;
use App\Models\Core\RoleModel;

/**
 * CalendarNotificationService
 *
 * Gestiona las notificaciones push/in-app para eventos del calendario.
 */
class CalendarNotificationService
{
    /**
     * Notifica a todos los administradores cuando un residente crea un evento.
     */
    public static function notifyAdminsNewEvent(int $userId, string $eventTitle, string $startDatetime)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $residentInfo = self::getResidentInfo($userId);
        $nombre = $residentInfo['name'];
        $unidad = $residentInfo['unit'];

        $body = "📅 El residente {$nombre} (Unidad {$unidad}) ha creado un evento: '{$eventTitle}' — Fecha: {$startDatetime}";

        self::notifyAdmins('Nuevo evento en calendario', $body, $condoId);
    }

    /**
     * Helper para obtener nombre del residente y unidad
     */
    public static function getResidentInfo(int $userId): array
    {
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        $name = $user ? trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) : 'Usuario';

        $unitName = 'Sin unidad';
        $residentModel = new ResidentModel();
        $res = $residentModel->where('user_id', $userId)->first();
        if ($res && !empty($res['unit_id'])) {
            $unitModel = new UnitModel();
            $unit = $unitModel->find($res['unit_id']);
            if ($unit) {
                $unitName = $unit['unit_number'] ?? $unitName;
            }
        }

        return ['name' => $name, 'unit' => $unitName];
    }

    /**
     * Helper para notificar a los admins
     */
    private static function notifyAdmins(string $title, string $body, int $condoId)
    {
        $roleModel = new RoleModel();
        $adminRole = $roleModel->where('name', 'ADMIN')->first();
        $adminRoleId = $adminRole ? $adminRole['id'] : 2;

        $ucrModel = new UserCondominiumRoleModel();
        $admins = $ucrModel->where('condominium_id', $condoId)
                           ->where('role_id', $adminRoleId)
                           ->findAll();

        foreach ($admins as $admin) {
            NotificationModel::notify(
                $condoId,
                $admin['user_id'],
                'calendar_event_new',
                $title,
                $body,
                ['type' => 'calendar']
            );
        }
    }

    /**
     * Notifica a TODOS los residentes cuando un Admin crea un evento público.
     * Sigue la misma estructura que Admin\CalendarController::dispatchCalendarPush
     */
    public static function notifyResidentsNewEvent(int $eventId, string $eventTitle, string $startDatetime)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $db = \Config\Database::connect();
        $residents = $db->table('residents')
            ->select('user_id')
            ->where('condominium_id', $condoId)
            ->where('user_id IS NOT NULL')
            ->get()->getResultArray();

        if (empty($residents)) return;

        $condoRow = $db->table('condominiums')->select('name')->where('id', $condoId)->get()->getRowArray();
        $condoName = $condoRow['name'] ?? 'Mi Condominio';

        try {
            $dt = new \DateTime($startDatetime);
            $dateFormatted = $dt->format('d/m/Y');
        } catch (\Throwable $e) {
            $dateFormatted = $startDatetime;
        }

        $pushTitle = "📅 Nuevo Evento · {$condoName}";
        $pushBody  = "{$eventTitle} — {$dateFormatted}";

        // Notificaciones in-app
        foreach ($residents as $r) {
            NotificationModel::notify(
                $condoId,
                $r['user_id'],
                'calendar_event',
                $pushTitle,
                $pushBody,
                ['type' => 'calendar', 'event_id' => $eventId]
            );
        }

        // Notificación Push FCM
        try {
            $pushService = new \App\Services\Notifications\PushNotificationService();
            $pushService->sendToCondominium($condoId, $pushTitle, $pushBody, [
                'type'         => 'calendar',
                'event_id'     => (string) $eventId,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'CalendarNotificationService (Push): ' . $e->getMessage());
        }
    }
}
