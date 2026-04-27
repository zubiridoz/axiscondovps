<?php

namespace App\Services;

use CodeIgniter\Email\Email;

class EmailService
{
    /**
     * Enviar correo de invitación a residente
     */
    public function sendResidentInvitation(string $toEmail, string $name, string $condoName, string $token): bool
    {
        $email = \Config\Services::email();

        $inviteUrl = base_url("invite/{$token}");
        
        $subject = "Bienvenido a {$condoName} en AXISCONDO";
        
        // Plantilla en código según especificación
        $message = "
        <p>Hola {$name},</p>
        <p>Has sido invitado a unirte a tu comunidad en AXISCONDO.</p>
        <p>Accede aquí para registrarte:</p>
        <p><a href='{$inviteUrl}'>{$inviteUrl}</a></p>
        <p>Una vez registrado quedarás automáticamente conectado a tu unidad dentro del condominio.</p>
        ";

        $email->setFrom('app@axiscondo.mx', 'AXISCONDO');
        $email->setTo($toEmail);
        $email->setSubject($subject);
        $email->setMessage($message);
        
        // Enviar y manejar (silenciosamente fallar en local si no hay SMTP, idealmente se loguea)
        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Fallo al enviar correo a: ' . $toEmail . ' -> ' . $email->printDebugger(['headers']));
            return false;
        }
    }
}
