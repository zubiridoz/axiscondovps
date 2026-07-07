<?php

namespace App\Services\Auth;

use App\Models\Core\UserModel;
use App\Models\Core\PasswordResetModel;
use App\Services\AuditService;

/**
 * PasswordResetService
 * 
 * Maneja la lógica de recuperación de contraseña para usuarios administrativos.
 * 
 * Medidas de seguridad implementadas:
 * - Token criptográficamente seguro (32 bytes / 256 bits de entropía)
 * - Almacenamiento como hash SHA-256 (nunca en texto plano)
 * - Expiración de 30 minutos
 * - Token de un solo uso (marcado con used_at)
 * - Invalidación de tokens previos al generar uno nuevo
 * - Rate limiting propio independiente del filter global
 * - Verificación de roles administrativos
 * - Mensajes genéricos para evitar enumeración de usuarios
 * - Protección contra timing attacks
 * - Protección contra race conditions (UPDATE atómico)
 */
class PasswordResetService
{
    /** Minutos de validez del token */
    private const TOKEN_EXPIRY_MINUTES = 30;

    /** Máximo de solicitudes por IP en ventana de tiempo */
    private const MAX_REQUESTS_PER_IP = 3;

    /** Ventana de rate limiting en minutos */
    private const RATE_LIMIT_WINDOW_MINUTES = 15;

    /** Roles que pueden usar password reset web */
    private const ALLOWED_ROLES = ['ADMIN', 'SUPER_ADMIN', 'FOUNDER'];

    /** Longitud en bytes del token aleatorio (256 bits de entropía) */
    private const TOKEN_BYTES = 32;

    /**
     * Solicita un enlace de restablecimiento de contraseña.
     * 
     * Siempre retorna true para evitar enumeración de usuarios.
     * El email solo se envía si el usuario existe, está activo y tiene rol admin.
     * 
     * @param string $email Email del usuario
     * @param string $ipAddress IP del solicitante
     * @return bool Siempre true (mensaje genérico)
     */
    public function requestReset(string $email, string $ipAddress): bool
    {
        $email = strtolower(trim($email));

        // Rate limiting propio basado en la tabla password_resets (independiente del filter global)
        if ($this->isRateLimited($ipAddress)) {
            // Retornar true silenciosamente para no revelar el rate limit
            return true;
        }

        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Verificar: usuario existe, está activo y tiene rol administrativo
        if (!$user || $user['status'] !== 'active' || !$this->hasAdminRole($user['id'])) {
            // Simular tiempo de procesamiento para evitar timing attack por SMTP
            usleep(random_int(400000, 800000));
            return true;
        }

        // Invalidar tokens previos activos del mismo usuario
        $this->invalidatePreviousTokens($user['id']);

        // Generar token seguro
        $plainToken = bin2hex(random_bytes(self::TOKEN_BYTES));
        $tokenHash = hash('sha256', $plainToken);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_EXPIRY_MINUTES . ' minutes'));

        // Guardar en BD
        $resetModel = new PasswordResetModel();
        $resetModel->insert([
            'user_id'    => $user['id'],
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt,
            'ip_address' => $ipAddress,
        ]);

        // Enviar email
        $this->sendResetEmail($user, $plainToken);

        // Auditoría
        (new AuditService())->logAction(
            (int)$user['id'],
            'PASSWORD_RESET_REQUESTED',
            'User',
            (int)$user['id']
        );

        return true;
    }

    /**
     * Valida un token de restablecimiento.
     * 
     * @param string $plainToken Token en texto plano recibido por URL
     * @return array|null Registro del token si es válido, null si no
     */
    public function validateToken(string $plainToken): ?array
    {
        // Validar formato antes de consultar BD
        if (!preg_match('/^[a-f0-9]{64}$/', $plainToken)) {
            return null;
        }

        $tokenHash = hash('sha256', $plainToken);

        $resetModel = new PasswordResetModel();
        $tokenRecord = $resetModel
            ->where('token_hash', $tokenHash)
            ->where('used_at IS NULL')
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->first();

        return $tokenRecord;
    }

    /**
     * Ejecuta el cambio de contraseña usando un token válido.
     * 
     * Usa UPDATE atómico para prevenir race conditions.
     * 
     * @param string $plainToken Token en texto plano
     * @param string $newPassword Nueva contraseña (ya validada por el controller)
     * @return bool true si el cambio fue exitoso
     */
    public function resetPassword(string $plainToken, string $newPassword): bool
    {
        $tokenRecord = $this->validateToken($plainToken);

        if (!$tokenRecord) {
            return false;
        }

        $db = \Config\Database::connect();

        // UPDATE atómico con condición: solo un request puede "ganar" la carrera
        $db->table('password_resets')
            ->where('id', $tokenRecord['id'])
            ->where('used_at IS NULL')
            ->update(['used_at' => date('Y-m-d H:i:s')]);

        if ($db->affectedRows() === 0) {
            // El token ya fue consumido por otro request concurrente
            return false;
        }

        // Solo después de "ganar" la carrera, actualizar la contraseña
        $userModel = new UserModel();
        $userModel->update($tokenRecord['user_id'], [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
        ]);

        // Revocar tokens API del usuario (cierra sesiones móviles)
        $tokenService = new TokenService();
        $tokenService->revokeAllUserTokens((int)$tokenRecord['user_id']);

        // Auditoría
        (new AuditService())->logAction(
            (int)$tokenRecord['user_id'],
            'PASSWORD_RESET_COMPLETED',
            'User',
            (int)$tokenRecord['user_id']
        );

        return true;
    }

    /**
     * Verifica si el usuario tiene un rol administrativo.
     */
    private function hasAdminRole(int $userId): bool
    {
        $db = \Config\Database::connect();
        $result = $db->table('user_condominium_roles')
            ->select('roles.name as role_name')
            ->join('roles', 'roles.id = user_condominium_roles.role_id', 'left')
            ->where('user_condominium_roles.user_id', $userId)
            ->get()
            ->getResultArray();

        foreach ($result as $row) {
            if (in_array(strtoupper($row['role_name']), self::ALLOWED_ROLES)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Invalida todos los tokens activos del usuario.
     */
    private function invalidatePreviousTokens(int $userId): void
    {
        $db = \Config\Database::connect();
        $db->table('password_resets')
            ->where('user_id', $userId)
            ->where('used_at IS NULL')
            ->update(['used_at' => date('Y-m-d H:i:s')]);
    }

    /**
     * Rate limiting propio basado en registros de la tabla password_resets.
     * Funciona independientemente del RateLimitFilter global (que puede estar en observationMode).
     */
    private function isRateLimited(string $ipAddress): bool
    {
        $db = \Config\Database::connect();
        $windowStart = date('Y-m-d H:i:s', strtotime('-' . self::RATE_LIMIT_WINDOW_MINUTES . ' minutes'));

        $recentAttempts = $db->table('password_resets')
            ->where('ip_address', $ipAddress)
            ->where('created_at >', $windowStart)
            ->countAllResults();

        return $recentAttempts >= self::MAX_REQUESTS_PER_IP;
    }

    /**
     * Envía el correo electrónico con el enlace de restablecimiento.
     */
    private function sendResetEmail(array $user, string $plainToken): void
    {
        $email = \Config\Services::email();

        $resetUrl = rtrim(base_url(), '/') . '/password/reset/' . $plainToken;
        $userName = htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8');
        $expiryMinutes = self::TOKEN_EXPIRY_MINUTES;

        $subject = 'Restablecer tu contraseña — AxisCondo';

        $message = <<<HTML
        <div style="font-family: 'Inter', Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #334155; background: #ffffff;">
            <!-- Header -->
            <div style="background: linear-gradient(135deg, #1D4C9D 0%, #153A7A 40%, #0E2A5C 100%); padding: 30px 40px; text-align: center; border-radius: 8px 8px 0 0;">
                <h1 style="color: #ffffff; font-size: 24px; font-weight: 800; margin: 0; letter-spacing: 0.5px;">AxisCondo</h1>
                <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin: 6px 0 0;">Administración Inteligente de Condominios</p>
            </div>

            <!-- Body -->
            <div style="padding: 35px 40px;">
                <h2 style="color: #0f172a; font-size: 20px; font-weight: 700; margin: 0 0 8px;">Restablece tu contraseña</h2>
                <p style="font-size: 15px; line-height: 1.7; color: #475569; margin: 0 0 20px;">
                    Hola <strong>{$userName}</strong>, recibimos una solicitud para restablecer la contraseña de tu cuenta de administrador.
                </p>

                <!-- Button -->
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{$resetUrl}" style="background: linear-gradient(135deg, #1D4C9D, #2960B8); color: #ffffff; text-decoration: none; padding: 14px 36px; border-radius: 10px; font-weight: 700; font-size: 15px; display: inline-block; box-shadow: 0 4px 14px rgba(29,76,157,0.35);">
                        Restablecer Contraseña
                    </a>
                </div>

                <!-- Expiry warning -->
                <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 14px 18px; border-radius: 0 6px 6px 0; margin: 25px 0;">
                    <p style="font-size: 13px; color: #92400e; margin: 0;">
                        <strong>⏱ Este enlace expira en {$expiryMinutes} minutos.</strong> Si no lo usas a tiempo, puedes solicitar uno nuevo.
                    </p>
                </div>

                <!-- Security note -->
                <p style="font-size: 14px; line-height: 1.7; color: #64748b; margin: 20px 0 0;">
                    Si tú no solicitaste este cambio, puedes ignorar este correo con seguridad. Tu contraseña no será modificada.
                </p>

                <p style="font-size: 13px; color: #94a3b8; margin: 25px 0 0;">
                    Si el botón no funciona, copia y pega el siguiente enlace en tu navegador:
                </p>
                <p style="font-size: 12px; color: #1D4C9D; word-break: break-all; margin: 5px 0 0;">
                    {$resetUrl}
                </p>
            </div>

            <!-- Footer -->
            <div style="background: #f8fafc; padding: 20px 40px; border-top: 1px solid #e2e8f0; border-radius: 0 0 8px 8px; text-align: center;">
                <p style="font-size: 11px; color: #94a3b8; margin: 0;">
                    Este es un mensaje automático generado por AxisCondo. No respondas a este correo.
                </p>
                <p style="font-size: 11px; color: #cbd5e1; margin: 6px 0 0;">
                    © AxisCondo — Administración Inteligente de Condominios
                </p>
            </div>
        </div>
HTML;

        $email->setFrom('hola@axiscondo.mx', 'AxisCondo');
        $email->setTo($user['email']);
        $email->setSubject($subject);
        $email->setMessage($message);

        if (!$email->send()) {
            log_message('error', '[PASSWORD_RESET] Fallo al enviar correo a: ' . $user['email'] . ' -> ' . $email->printDebugger(['headers']));
        }
    }
}
