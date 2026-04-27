<?php

namespace App\Models\Tenant;

class NotificationModel extends BaseTenantModel
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'condominium_id',
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'read_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;

    /**
     * Create a notification for a user
     */
    public static function notify(int $condoId, int $userId, string $type, string $title, string $body, array $data = [], bool $sendPush = true): int
    {
        $model = new self();
        $id = $model->insert([
            'condominium_id' => $condoId,
            'user_id'        => $userId,
            'type'           => $type,
            'title'          => $title,
            'body'           => $body,
            'data'           => !empty($data) ? json_encode($data) : null,
        ]);

        if ($sendPush) {
            try {
                $pushService = new \App\Services\Notifications\PushNotificationService();
                $pushService->sendToUser($userId, $title, $body, $data);
            } catch (\Throwable $e) {
                log_message('error', "[FCM_ERROR] Error enviando push en notify(): " . $e->getMessage());
            }
        }

        return $id;
    }
}
