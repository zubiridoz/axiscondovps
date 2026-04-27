<?php

namespace App\Services;

use App\Models\Tenant\PaymentReminderModel;

class PaymentReminderService
{
    /**
     * Get all active reminders for a condominium, initializing defaults if none exist.
     *
     * @param int $condominiumId
     * @return array
     */
    public static function getRemindersForCondominium(int $condominiumId): array
    {
        $model = new PaymentReminderModel();
        
        $reminders = $model->where('condominium_id', $condominiumId)
                           ->orderBy('created_at', 'ASC')
                           ->findAll();
                           
        if (empty($reminders)) {
            self::initializeDefaults($condominiumId);
            $reminders = $model->where('condominium_id', $condominiumId)
                               ->orderBy('created_at', 'ASC')
                               ->findAll();
        }
        
        return $reminders;
    }

    /**
     * Creates the 3 default reminders.
     *
     * @param int $condominiumId
     * @return void
     */
    private static function initializeDefaults(int $condominiumId): void
    {
        $model = new PaymentReminderModel();
        
        $defaults = [
            [
                'condominium_id' => $condominiumId,
                'trigger_type'   => 'start_of_month',
                'trigger_value'  => 1,
                'message_title'  => 'Recordatorio Mensual de Cuota',
                'message_body'   => '¡Es inicio de mes! Recuerda pagar tu cuota de mantenimiento antes de la fecha de vencimiento.',
                'is_active'      => 1,
            ],
            [
                'condominium_id' => $condominiumId,
                'trigger_type'   => 'days_before_due',
                'trigger_value'  => 2,
                'message_title'  => 'Pago Por Vencer',
                'message_body'   => 'Recordatorio amigable: Tu cuota de mantenimiento vence en {x} días.',
                'is_active'      => 1,
            ],
            [
                'condominium_id' => $condominiumId,
                'trigger_type'   => 'due_date',
                'trigger_value'  => 0,
                'message_title'  => 'Pago Vence Hoy',
                'message_body'   => 'Tu cuota de mantenimiento vence hoy. Por favor realiza tu pago para evitar cargos por mora.',
                'is_active'      => 1,
            ]
        ];

        $model->insertBatch($defaults);
    }
}
