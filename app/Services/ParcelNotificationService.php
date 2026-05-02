<?php

namespace App\Services;

use App\Models\Tenant\NotificationModel;
use App\Services\Notifications\PushNotificationService;

/**
 * ParcelNotificationService
 *
 * Servicio desacoplado para enviar notificaciones push y in-app
 * a los residentes de una unidad cuando un guardia registra
 * la recepción o entrega de un paquete.
 *
 * Sigue el mismo patrón de AccessNotificationService:
 * - 100% fail-safe
 * - Multi-tenant seguro (DISTINCT user_id)
 * - Consulta condo name fuera del loop
 */
class ParcelNotificationService
{
    /**
     * Notifica a los residentes que un paquete llegó a la caseta.
     *
     * @param int    $unitId         ID de la unidad destinataria
     * @param int    $condominiumId  ID del condominio (tenant)
     * @param int    $quantity       Cantidad de paquetes
     * @param string $parcelType     Tipo de paquete (Paquete, Sobre, Caja, etc.)
     * @param string $courier        Proveedor de paquetería (DHL, FedEx, etc.)
     * @param string $deviceName     Nombre del dispositivo/caseta que recibió
     * @param int|null $parcelId     ID del paquete para trazabilidad
     */
    public static function notifyArrival(
        int $unitId,
        int $condominiumId,
        int $quantity,
        string $parcelType,
        string $courier,
        string $deviceName = 'Portería',
        ?int $parcelId = null,
        ?string $deliveryPin = null
    ): void {
        if (empty($unitId) || empty($condominiumId)) {
            log_message('warning', '[PARCEL_NOTIFY] Datos insuficientes para notificación de llegada');
            return;
        }

        $condoName = self::getCondominiumName($condominiumId);

        $title = 'Tu paquete ha llegado';
        $body = "Paquete recibido en {$deviceName} vía {$courier} - Tienes {$quantity} {$parcelType} recibido(s)";

        // 🔐 Incluir PIN de entrega en el body de la notificación
        if (!empty($deliveryPin)) {
            $body .= "\n🔐 PIN de entrega: {$deliveryPin}";
        }

        $data = [
            'tipo' => 'paquete_entrada',
            'condominio_id' => $condominiumId,
            'parcel_id' => $parcelId ?? 0,
            'unit_id' => $unitId,
        ];

        // Incluir PIN en data payload para que Flutter pueda mostrarlo
        if (!empty($deliveryPin)) {
            $data['delivery_pin'] = $deliveryPin;
        }

        log_message('info', "[PARCEL_NOTIFY] Sending ARRIVAL notification — unit_id={$unitId}, condominium_id={$condominiumId}, qty={$quantity}, type={$parcelType}, courier={$courier}, pin=" . ($deliveryPin ? 'YES' : 'NO'));

        self::sendToUnitResidents($unitId, $condominiumId, $title, $body, $data, 'parcel_arrival');
    }

    /**
     * Notifica a los residentes que un paquete fue entregado/recogido.
     *
     * @param int    $unitId         ID de la unidad
     * @param int    $condominiumId  ID del condominio (tenant)
     * @param string $parcelType     Tipo de paquete
     * @param string $pickedUpName   Nombre de quien recogió
     * @param int|null $parcelId     ID del paquete para trazabilidad
     */
    public static function notifyDelivery(
        int $unitId,
        int $condominiumId,
        string $parcelType,
        string $pickedUpName,
        ?int $parcelId = null
    ): void {
        if (empty($unitId) || empty($condominiumId)) {
            log_message('warning', '[PARCEL_NOTIFY] Datos insuficientes para notificación de entrega');
            return;
        }

        $condoName = self::getCondominiumName($condominiumId);

        $title = 'Paquete entregado al residente';
        $body = "📦 Paquete entregado al residente {$parcelType} recogido por: {$pickedUpName}";

        $data = [
            'tipo' => 'paquete_salida',
            'condominio_id' => $condominiumId,
            'parcel_id' => $parcelId ?? 0,
            'unit_id' => $unitId,
        ];

        log_message('info', "[PARCEL_NOTIFY] Sending DELIVERY notification — unit_id={$unitId}, condominium_id={$condominiumId}, type={$parcelType}, pickedBy={$pickedUpName}");

        self::sendToUnitResidents($unitId, $condominiumId, $title, $body, $data, 'parcel_delivered');
    }

    /**
     * Envía push + notificación in-app a TODOS los residentes activos de la unidad.
     */
    private static function sendToUnitResidents(int $unitId, int $condominiumId, string $title, string $body, array $data, string $notificationType): void
    {
        try {
            $db = \Config\Database::connect();
            $residents = $db->table('residents')
                ->select('DISTINCT(user_id) as user_id')
                ->where('unit_id', $unitId)
                ->where('is_active', 1)
                ->where('user_id IS NOT NULL')
                ->get()
                ->getResultArray();

            if (empty($residents)) {
                log_message('warning', "[PARCEL_NOTIFY] No active residents found for unit_id={$unitId}");
                return;
            }

            log_message('info', "[PARCEL_NOTIFY] Found " . count($residents) . " resident(s) for unit_id={$unitId}");

            $pushService = new PushNotificationService();

            foreach ($residents as $resident) {
                $userId = (int) $resident['user_id'];

                // Push FCM — fail-safe
                try {
                    $pushService->sendToUser($userId, $title, $body, $data);
                    log_message('info', "[PARCEL_NOTIFY][PUSH_OK] user_id={$userId}");
                } catch (\Throwable $e) {
                    log_message('error', "[PARCEL_NOTIFY][PUSH_FAIL] user_id={$userId} — " . $e->getMessage());
                }

                // Notificación in-app — fail-safe
                try {
                    NotificationModel::notify(
                        $condominiumId,
                        $userId,
                        $notificationType,
                        $title,
                        $body,
                        $data
                    );
                    log_message('info', "[PARCEL_NOTIFY][DB_OK] user_id={$userId}");
                } catch (\Throwable $e) {
                    log_message('error', "[PARCEL_NOTIFY][DB_FAIL] user_id={$userId} — " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            log_message('error', "[PARCEL_NOTIFY][CRITICAL] " . $e->getMessage());
        }
    }

    /**
     * Obtiene el nombre del condominio.
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
            log_message('error', "[PARCEL_NOTIFY] Error fetching condo name: " . $e->getMessage());
            return 'Tu Condominio';
        }
    }
}
