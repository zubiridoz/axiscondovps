<?php

namespace App\Services;

use App\Models\Tenant\NotificationModel;
use App\Models\Tenant\UserCondominiumRoleModel;
use App\Models\Core\UserModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\UnitModel;
use App\Models\Core\RoleModel;

class PollNotificationService
{
    /**
     * Notifica a todos los residentes sobre una nueva encuesta.
     */
    public static function notifyNewPoll(string $title)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $residentModel = new ResidentModel();
        $residents = $residentModel->where('is_active', 1)->findAll();

        foreach ($residents as $res) {
            if (!empty($res['user_id'])) {
                NotificationModel::notify(
                    $condoId,
                    $res['user_id'],
                    'poll_new',
                    'Nueva encuesta',
                    "📊 Nueva encuesta disponible: {$title}",
                    ['type' => 'poll']
                );
            }
        }
    }

    /**
     * Notifica a todos los administradores que un residente ha votado.
     */
    public static function notifyVote(int $userId, string $pollTitle)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $residentInfo = self::getResidentInfo($userId);
        $nombre = $residentInfo['name'];
        $unidad = $residentInfo['unit'];

        $body = "🗳️ El residente {$nombre} de la unidad {$unidad} votó en la encuesta '{$pollTitle}'";

        self::notifyAdmins('Nuevo voto en encuesta', $body, $condoId);
    }

    /**
     * Notifica a todos los administradores que un residente cambió su voto.
     */
    public static function notifyVoteChange(int $userId, string $pollTitle)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $residentInfo = self::getResidentInfo($userId);
        $nombre = $residentInfo['name'];

        $body = "🔄 El residente {$nombre} cambió su voto en la encuesta '{$pollTitle}'";

        self::notifyAdmins('Cambio de voto', $body, $condoId);
    }

    /**
     * Notifica a todos los residentes que una encuesta finalizó.
     */
    public static function notifyPollFinished(string $title)
    {
        $condoId = TenantService::getInstance()->getTenantId();
        if (!$condoId) return;

        $residentModel = new ResidentModel();
        $residents = $residentModel->where('is_active', 1)->findAll();

        foreach ($residents as $res) {
            if (!empty($res['user_id'])) {
                NotificationModel::notify(
                    $condoId,
                    $res['user_id'],
                    'poll_finished',
                    'Encuesta finalizada',
                    "✅ La encuesta '{$title}' ha finalizado",
                    ['type' => 'poll']
                );
            }
        }
    }

    /**
     * Helper para obtener nombre del residente y unidad
     */
    private static function getResidentInfo(int $userId): array
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
                'poll_activity',
                $title,
                $body,
                ['type' => 'poll']
            );
        }
    }
}
