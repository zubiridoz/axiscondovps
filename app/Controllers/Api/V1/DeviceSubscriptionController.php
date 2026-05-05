<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;

class DeviceSubscriptionController extends ResourceController
{
    /**
     * POST /api/v1/devices/subscribe
     */
    public function subscribe()
    {
        log_message('info', '[FCM_SUB] ========== SUBSCRIBE REQUEST ==========');

        // 1. Obtener user_id del filtro de autenticación
        $userId = $this->request->userId ?? null;
        log_message('info', '[FCM_SUB] user_id from auth filter: ' . ($userId ?? 'NULL'));

        if (!$userId) {
            log_message('error', '[FCM_SUB] No user_id — auth filter failed');
            return $this->response->setJSON(['status' => 'error', 'message' => 'No autenticado'])->setStatusCode(401);
        }

        // 2. Leer body — soportar JSON y form-data
        $rawBody = $this->request->getBody();
        log_message('info', '[FCM_SUB] Raw body: ' . $rawBody);

        $json = json_decode($rawBody, true);
        if (!is_array($json)) {
            $json = [];
        }

        $fcmToken   = $json['fcm_token']   ?? $this->request->getPost('fcm_token') ?? '';
        $deviceInfo = $json['device_info'] ?? $this->request->getPost('device_info') ?? 'Flutter Mobile';
        $platform   = $json['platform']    ?? $this->request->getPost('platform') ?? 'android';

        log_message('info', '[FCM_SUB] fcm_token: ' . substr($fcmToken, 0, 40) . '...');
        log_message('info', '[FCM_SUB] device_info: ' . $deviceInfo);
        log_message('info', '[FCM_SUB] platform: ' . $platform);

        if (empty($fcmToken)) {
            log_message('error', '[FCM_SUB] Token FCM vacío');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Token FCM vacío'])->setStatusCode(400);
        }

        // 3. Obtener condominium_id
        $condominiumId = null;
        try {
            $condominiumId = \App\Services\TenantService::getInstance()->getTenantId();
        } catch (\Throwable $e) {
            log_message('warning', '[FCM_SUB] TenantService error: ' . $e->getMessage());
        }

        // Fallback: buscar en user_condominium_roles
        if (!$condominiumId) {
            $db = \Config\Database::connect();
            $pivot = $db->table('user_condominium_roles')->where('user_id', $userId)->get()->getRow();
            $condominiumId = $pivot->condominium_id ?? null;
            log_message('info', '[FCM_SUB] Fallback condominium_id from pivot: ' . ($condominiumId ?? 'NULL'));
        }

        log_message('info', '[FCM_SUB] condominium_id: ' . ($condominiumId ?? 'NULL'));

        // 4. UPSERT con Query Builder y Transacciones para evitar condiciones de carrera
        $db = \Config\Database::connect();
        $table = $db->table('device_push_subscriptions');

        // Iniciar transacción segura
        $db->transStart();

        // Buscar si ya existe un registro con este token EXACTO (identificador único de instalación)
        $existing = $table->where('fcm_token', $fcmToken)->get()->getRow();

        if ($existing) {
            // Actualizar el registro existente (Traspaso de token a nuevo usuario o simple actualización de datos)
            $db->table('device_push_subscriptions')
                ->where('id', $existing->id)
                ->update([
                    'user_id'        => $userId,
                    'condominium_id' => $condominiumId,
                    'device_info'    => $deviceInfo,
                    'platform'       => $platform,
                    'updated_at'     => date('Y-m-d H:i:s'),
                ]);
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', '[FCM_SUB] ❌ Transaction failed on UPDATE token');
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error al actualizar token'])->setStatusCode(500);
            }

            log_message('info', '[FCM_SUB] ✅ Token UPDATED (id=' . $existing->id . ') - Multiple devices supported');

            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Token FCM actualizado correctamente.',
                'id'      => $existing->id,
            ]);
        }

        // Si el token no existe, lo insertamos como NUEVO.
        // NOTA: Se eliminó la validación anterior que borraba el token del usuario si ya tenía uno.
        // Esto permite que el mismo usuario tenga múltiples dispositivos simultáneamente.

        // Insertar nuevo registro
        $db->table('device_push_subscriptions')->insert([
            'user_id'        => $userId,
            'condominium_id' => $condominiumId,
            'fcm_token'      => $fcmToken,
            'device_info'    => $deviceInfo,
            'platform'       => $platform,
            'endpoint'       => '',
            'p256dh_key'     => '',
            'auth_key'       => '',
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $newId = $db->insertID();
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            log_message('error', '[FCM_SUB] ❌ Transaction failed on INSERT token');
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error al registrar token'])->setStatusCode(500);
        }

        log_message('info', '[FCM_SUB] ✅ Token INSERTED (id=' . $newId . ') - Multiple devices supported');

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Dispositivo registrado correctamente.',
            'id'      => $newId,
        ]);
    }

    /**
     * DELETE /api/v1/devices/unsubscribe
     */
    public function unsubscribe()
    {
        $rawBody = $this->request->getBody();
        $json = json_decode($rawBody, true);
        $fcmToken = $json['fcm_token'] ?? $this->request->getVar('fcm_token') ?? '';

        if (!empty($fcmToken)) {
            $db = \Config\Database::connect();
            $db->table('device_push_subscriptions')->where('fcm_token', $fcmToken)->delete();
            log_message('info', '[FCM_SUB] Token unsubscribed');
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Suscripción cancelada.']);
    }
}
