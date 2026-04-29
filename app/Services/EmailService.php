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
        
        $subject = "Te invitaron a unirte a {$condoName} en AxisCondo";
        
        // Plantilla premium basada en la opción B solicitada
        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #334155;'>
            <div style='text-align: center; margin-bottom: 20px;'>
                <h2 style='color: #1D4C9D; margin-bottom: 0;'>Bienvenido a {$condoName}</h2>
                <p style='color: #64748b; font-size: 14px; margin-top: 5px;'>Tu acceso a AxisCondo</p>
            </div>
            
            <p style='font-size: 16px;'>Hola <strong>{$name}</strong>,</p>
            
            <p style='font-size: 15px; line-height: 1.6;'>
                La administración de <strong>{$condoName}</strong> te ha invitado a unirte a la comunidad en <strong>AxisCondo</strong>, la app para gestionar tu condominio.
            </p>
            
            <div style='background-color: #f1f5f9; padding: 20px; border-radius: 8px; margin: 25px 0; text-align: center;'>
                <p style='margin-top: 0; font-size: 14px; color: #64748b;'>Tu código de invitación:</p>
                <div style='font-size: 24px; font-weight: bold; letter-spacing: 2px; color: #1D4C9D; margin-bottom: 15px;'>{$token}</div>
                
                <p style='font-size: 14px; margin-bottom: 15px;'>Para activar tu cuenta, regístrate directamente desde:</p>
                
                <a href='https://app.axiscondo.mx/login?token={$token}#activar' style='background-color: #1D4C9D; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold; display: inline-block;'>Activar Cuenta de Residente</a>
                
                <div style='background-color: #e0e7ff; border-left: 4px solid #4f46e5; padding: 12px 15px; margin-top: 20px; text-align: left; border-radius: 0 4px 4px 0;'>
                    <p style='font-size: 13px; color: #3730a3; margin: 0;'>
                        <strong>💡 Nota rápida:</strong> Al hacer clic en el botón de arriba, irás directo a la pestaña de <strong>ACTIVAR CUENTA RESIDENTE</strong> y tu código se pegará automáticamente. Solo tendrás que elegir tu contraseña.
                    </p>
                </div>
            </div>
            
            <p style='font-size: 15px; line-height: 1.6;'>
                Descarga la app AxisCondo para acceder a: pagos, reservas de amenidades, anuncios, estados de cuenta y más.
            </p>
            
            <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 30px 0;'>
            
            <p style='font-size: 12px; color: #94a3b8; text-align: center;'>
                Este es un mensaje automático generado por AxisCondo en nombre de {$condoName}.
            </p>
        </div>
        ";

        $email->setFrom('hola@axiscondo.mx', 'AxisCondo');
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
