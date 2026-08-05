<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class PushAnnouncementCommand extends BaseCommand
{
    protected $group       = 'Notifications';
    protected $name        = 'push:announcement';
    protected $description = 'Dispara notificaciones push de un anuncio en segundo plano.';
    protected $usage       = 'push:announcement [announcement_id] [condominium_id] [exclude_user_id] [category] [content]';

    public function run(array $params)
    {
        if (count($params) < 5) {
            CLI::error('Faltan parámetros. Uso: ' . $this->usage);
            return;
        }

        $announcementId = (int)$params[0];
        $condominiumId  = (int)$params[1];
        $excludeUserId  = (int)$params[2];
        $category       = $params[3];
        
        // El contenido puede tener espacios, así que tomamos todo el resto de los parámetros y los unimos
        $contentParams = array_slice($params, 4);
        $content = implode(' ', $contentParams);

        // Decodificar Base64 por si enviamos contenido complejo con comillas
        $content = base64_decode($content);

        log_message('info', '[PUSH-CLI] ========== DISPATCH START ==========');
        log_message('info', "[PUSH-CLI] Announcement #{$announcementId}, category={$category}");

        try {
            $db = \Config\Database::connect();

            // Obtener todos los user_id de residentes de manera única
            $residents = $db->table('residents')
                ->select('user_id')
                ->distinct()
                ->where('condominium_id', $condominiumId)
                ->where('user_id IS NOT NULL')
                ->get()->getResultArray();

            log_message('info', '[PUSH-CLI] Residents found: ' . count($residents));

            if (empty($residents)) {
                log_message('warning', '[PUSH-CLI] No residents in condominium - aborting');
                return;
            }

            // Obtener nombre del condominio para el título
            $condoRow = $db->table('condominiums')->select('name')->where('id', $condominiumId)->get()->getRowArray();
            $condoName = $condoRow['name'] ?? 'Mi Condominio';

            // Título según categoría + nombre del condominio
            $categoryLabels = [
                'general'       => ["\xF0\x9F\x93\xA2", 'Nuevo Aviso'],
                'mantenimiento' => ["\xF0\x9F\x94\xA7", 'Aviso de Mantenimiento'],
                'urgente'       => ["\xF0\x9F\x9A\xA8", 'Aviso Urgente'],
                'evento'        => ["\xF0\x9F\x93\x85", 'Nuevo Evento'],
            ];
            $catInfo = $categoryLabels[$category] ?? $categoryLabels['general'];
            $pushTitle = "{$catInfo[0]} {$catInfo[1]} - {$condoName}";
            $pushBody  = mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 200);

            log_message('info', "[PUSH-CLI] Title: {$pushTitle}");

            // Insertar notificaciones en DB para pantalla "Avisos"
            $now = date('Y-m-d H:i:s');
            $notifType = ($category === 'urgente') ? 'urgent' : 'announcement';
            $insertedCount = 0;

            foreach ($residents as $r) {
                if ((int)$r['user_id'] === $excludeUserId) continue;

                $inserted = $db->table('notifications')->insert([
                    'condominium_id' => $condominiumId,
                    'user_id'        => $r['user_id'],
                    'type'           => $notifType,
                    'title'          => $pushTitle,
                    'body'           => $pushBody,
                    'data'           => json_encode([
                        'announcement_id' => $announcementId,
                        'category'        => $category,
                    ]),
                    'read_at'    => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                if ($inserted) $insertedCount++;
            }

            log_message('info', "[PUSH-CLI] Notifications inserted in DB: {$insertedCount}/" . count($residents));

            // Verificar que hay tokens FCM antes de enviar
            $tokenCount = $db->table('device_push_subscriptions')
                ->where('condominium_id', $condominiumId)
                ->where('fcm_token IS NOT NULL')
                ->where('fcm_token !=', '')
                ->countAllResults();

            log_message('info', "[PUSH-CLI] FCM tokens available: {$tokenCount}");

            if ($tokenCount > 0) {
                // Enviar push FCM
                $pushService = new \App\Services\Notifications\PushNotificationService();
                $result = $pushService->sendToCondominium($condominiumId, $pushTitle, $pushBody, [
                    'type'            => 'announcement',
                    'announcement_id' => (string) $announcementId,
                    'category'        => $category,
                    'click_action'    => 'FLUTTER_NOTIFICATION_CLICK',
                ]);
                log_message('info', '[PUSH-CLI] FCM send result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            } else {
                log_message('warning', '[PUSH-CLI] No FCM tokens found - push NOT sent');
            }

            log_message('info', '[PUSH-CLI] ========== DISPATCH END ==========');

        } catch (\Throwable $e) {
            log_message('error', '[PUSH-CLI] Exception: ' . $e->getMessage());
            log_message('error', '[PUSH-CLI] Stack: ' . $e->getTraceAsString());
            CLI::error('Error: ' . $e->getMessage());
        }
    }
}
