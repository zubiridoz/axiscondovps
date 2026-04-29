<?php

namespace App\Services;

use App\Models\Tenant\ResidentInvitationModel;
use App\Models\Tenant\CondominiumModel;
use CodeIgniter\I18n\Time;

class InvitationService
{
    protected ResidentInvitationModel $invitationModel;
    protected CondominiumModel $condoModel;
    protected EmailService $emailService;

    public function __construct()
    {
        $this->invitationModel = new ResidentInvitationModel();
        $this->condoModel = new CondominiumModel();
        $this->emailService = new EmailService();
    }

    /**
     * Crear una invitación individual
     */
    public function createInvitation(int $condoId, array $data, int $invitedBy, bool $sendEmail = true): ?string
    {
        // 1. Validar si ya existe invitación pendiente
        $existing = $this->invitationModel
            ->where('condominium_id', $condoId)
            ->where('email', $data['email'])
            ->where('invitation_status', 'pending')
            ->first();

        if ($existing) {
            return 'Ya existe una invitación pendiente para este correo.';
        }

        // 2. Generar token seguro
        $token = bin2hex(random_bytes(32));

        // 3. Preparar expiración (7 días como regla de la especificación técnica)
        $expiresAt = Time::now()->addDays(7)->format('Y-m-d H:i:s');

        // 4. Guardar en Base de Datos
        $insertData = [
            'condominium_id'    => $condoId,
            'unit_id'           => !empty($data['unit_id']) ? $data['unit_id'] : null,
            'email'             => $data['email'],
            'name'              => $data['name'],
            'role'              => $data['role'], // 'owner', 'tenant', 'admin'
            'token'             => $token,
            'invitation_status' => 'pending',
            'invited_by'        => $invitedBy,
            'invited_at'        => Time::now()->format('Y-m-d H:i:s'),
            'expires_at'        => $expiresAt
        ];

        if(!$this->invitationModel->insert($insertData)) {
            return 'No se pudo generar la invitación.';
        }

        // 5. Intentar enviar el Email (solo si se solicita)
        if ($sendEmail) {
            $condo = $this->condoModel->find($condoId);
            $condoName = $condo ? $condo['name'] : 'tu comunidad';
            
            $emailSent = $this->emailService->sendResidentInvitation(
                $data['email'],
                $data['name'],
                $condoName,
                $token
            );

            if (!$emailSent) {
                log_message('error', 'Invitación generada pero el correo no pudo ser enviado a ' . $data['email']);
                return 'La invitación se generó, pero no pudimos enviar el correo. Verifica tu configuración SMTP.';
            }
        }

        return null; // OK
    }
}
