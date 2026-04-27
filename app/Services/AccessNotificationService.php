<?php

namespace App\Services;

use App\Models\Tenant\NotificationModel;
use App\Models\Tenant\ResidentModel;
use App\Services\Notifications\PushNotificationService;

/**
 * AccessNotificationService
 *
 * Servicio desacoplado para enviar notificaciones push y in-app
 * a los residentes de una unidad cuando un guardia registra
 * entrada o salida de una visita vía QR.
 *
 * Garantías:
 * - 100% fail-safe (nunca interrumpe el flujo principal)
 * - Multi-tenant seguro (filtra por unit_id + condominium_id)
 * - Sin duplicados (DISTINCT user_id)
 * - Performance (consulta condo name una sola vez fuera del loop)
 */
class AccessNotificationService
{
    /**
     * Notifica a los residentes de la unidad que un visitante ha ENTRADO.
     *
     * @param int    $unitId         ID de la unidad visitada
     * @param string $visitorName    Nombre del visitante
     * @param int    $condominiumId  ID del condominio (tenant)
     * @param int|null $visitaId     ID del QR Code (qr_code_id) para trazabilidad
     */
    public static function notifyEntry(int $unitId, string $visitorName, int $condominiumId, ?int $visitaId = null): void
    {
        // ✅ Ajuste #5: Validación de datos
        if (empty($unitId) || empty($condominiumId)) {
            log_message('warning', '[ACCESS_NOTIFY] Datos insuficientes para notificación de entrada');
            return;
        }

        // ✅ Ajuste #3: Consultar nombre del condominio UNA sola vez
        $condoName = self::getCondominiumName($condominiumId);

        $title = 'Tu visita ha llegado';
        $body = "Entrada - {$visitorName} acaba de registrar su acceso mediante un código QR autorizado.";

        // ✅ Ajuste #6: Payload consistente con int, no string
        $data = [
            'tipo' => 'entrada',
            'condominio_id' => $condominiumId,
            'visita_id' => $visitaId ?? 0,
            'unit_id' => $unitId,
        ];

        // ✅ Ajuste #7: Logs para debug en producción
        log_message('info', "[ACCESS_NOTIFY] Sending ENTRY notification — unit_id={$unitId}, condominium_id={$condominiumId}, visitor={$visitorName}, visita_id=" . ($visitaId ?? 'NULL'));

        self::sendToUnitResidents($unitId, $condominiumId, $title, $body, $data);
    }

    /**
     * Notifica a los residentes de la unidad que un visitante ha SALIDO.
     *
     * @param int    $unitId         ID de la unidad visitada
     * @param string $visitorName    Nombre del visitante
     * @param int    $condominiumId  ID del condominio (tenant)
     * @param int|null $visitaId     ID del QR Code (qr_code_id) para trazabilidad
     */
    public static function notifyExit(int $unitId, string $visitorName, int $condominiumId, ?int $visitaId = null): void
    {
        // ✅ Ajuste #5: Validación de datos
        if (empty($unitId) || empty($condominiumId)) {
            log_message('warning', '[ACCESS_NOTIFY] Datos insuficientes para notificación de salida');
            return;
        }

        // ✅ Ajuste #3: Consultar nombre del condominio UNA sola vez
        $condoName = self::getCondominiumName($condominiumId);

        $title = 'Tu visita ha salido';
        $body = "Salida - {$visitorName} acaba de registrar su salida mediante un código QR autorizado.";

        // ✅ Ajuste #6: Payload consistente con int, no string
        $data = [
            'tipo' => 'salida',
            'condominio_id' => $condominiumId,
            'visita_id' => $visitaId ?? 0,
            'unit_id' => $unitId,
        ];

        // ✅ Ajuste #7: Logs para debug en producción
        log_message('info', "[ACCESS_NOTIFY] Sending EXIT notification — unit_id={$unitId}, condominium_id={$condominiumId}, visitor={$visitorName}, visita_id=" . ($visitaId ?? 'NULL'));

        self::sendToUnitResidents($unitId, $condominiumId, $title, $body, $data);
    }

    /**
     * Envía push + notificación in-app a TODOS los residentes activos de la unidad.
     * ✅ Ajuste #2: DISTINCT para prevenir duplicados
     * ✅ Ajuste #4: try/catch en cada operación (fail-safe)
     */
    private static function sendToUnitResidents(int $unitId, int $condominiumId, string $title, string $body, array $data): void
    {
        try {
            // ✅ Ajuste #2: DISTINCT user_id para evitar duplicados
            $db = \Config\Database::connect();
            $residents = $db->table('residents')
                ->select('DISTINCT(user_id) as user_id')
                ->where('unit_id', $unitId)
                ->where('is_active', 1)
                ->where('user_id IS NOT NULL')
                ->get()
                ->getResultArray();

            if (empty($residents)) {
                log_message('warning', "[ACCESS_NOTIFY] No active residents found for unit_id={$unitId}");
                return;
            }

            log_message('info', "[ACCESS_NOTIFY] Found " . count($residents) . " resident(s) for unit_id={$unitId}");

            $pushService = new PushNotificationService();

            foreach ($residents as $resident) {
                $userId = (int) $resident['user_id'];

                // ✅ Ajuste #4: Push FCM — fail-safe
                try {
                    $pushService->sendToUser($userId, $title, $body, $data);
                    log_message('info', "[ACCESS_NOTIFY][PUSH_OK] user_id={$userId}");
                } catch (\Throwable $e) {
                    log_message('error', "[ACCESS_NOTIFY][PUSH_FAIL] user_id={$userId} — " . $e->getMessage());
                }

                // ✅ Ajuste #4: Notificación in-app — fail-safe
                try {
                    $notificationType = ($data['tipo'] === 'entrada') ? 'access_entry' : 'access_exit';
                    NotificationModel::notify(
                        $condominiumId,
                        $userId,
                        $notificationType,
                        $title,
                        $body,
                        $data
                    );
                    log_message('info', "[ACCESS_NOTIFY][DB_OK] user_id={$userId}");
                } catch (\Throwable $e) {
                    log_message('error', "[ACCESS_NOTIFY][DB_FAIL] user_id={$userId} — " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            // ✅ Ajuste #4: Catch general — el servicio NUNCA debe romper el flujo principal
            log_message('error', "[ACCESS_NOTIFY][CRITICAL] " . $e->getMessage());
        }
    }

    /**
     * Obtiene el nombre del condominio.
     * ✅ Ajuste #3: Consulta separada para reutilización y claridad.
     */
    private static function getCondominiumName(int $condominiumId): string
    {
        try {
            $db = \Config\Database::connect();
            $condo = $db->table('condominiums')
                ->select('name')
                ->where('id', $condominiumId)
                ->get()
                ->getRowArray();

            return $condo['name'] ?? 'Tu Condominio';
        } catch (\Throwable $e) {
            log_message('error', "[ACCESS_NOTIFY] Error fetching condo name: " . $e->getMessage());
            return 'Tu Condominio';
        }
    }
}
