<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Services\Auth\PasswordResetService;

/**
 * PasswordResetController
 * 
 * Maneja el flujo de recuperación de contraseña para administradores.
 * 4 endpoints: mostrar formulario, enviar enlace, mostrar reset, procesar reset.
 */
class PasswordResetController extends BaseController
{
    /** Longitud mínima de contraseña (consistente con el registro existente) */
    private const MIN_PASSWORD_LENGTH = 6;

    /** Longitud máxima de contraseña (límite real de bcrypt) */
    private const MAX_PASSWORD_LENGTH = 72;

    /**
     * GET /password/forgot
     * Muestra el formulario para solicitar el enlace de restablecimiento.
     */
    public function showForgotForm()
    {
        // Si ya está logueado, redirigir al dashboard
        if (session()->get('is_logged_in')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('auth/forgot_password');
    }

    /**
     * POST /password/forgot
     * Procesa la solicitud y envía el enlace por email.
     */
    public function sendResetLink()
    {
        $email = trim((string)$this->request->getPost('email'));

        // Validación básica de formato
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Por favor, ingresa un correo electrónico válido.');
        }

        $service = new PasswordResetService();
        $service->requestReset($email, $this->request->getIPAddress());

        // Siempre mostrar mensaje genérico (no revelar si el email existe)
        return redirect()->to('/password/forgot')->with(
            'success',
            'Si existe una cuenta con ese correo, recibirás un enlace para restablecer tu contraseña en los próximos minutos.'
        );
    }

    /**
     * GET /password/reset/{token}
     * Muestra el formulario para establecer la nueva contraseña.
     */
    public function showResetForm(string $token = '')
    {
        // Validar formato del token antes de consultar BD
        if (empty($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return redirect()->to('/password/forgot')->with(
                'error',
                'El enlace no es válido. Solicita uno nuevo.'
            );
        }

        $service = new PasswordResetService();
        $tokenRecord = $service->validateToken($token);

        if (!$tokenRecord) {
            return redirect()->to('/password/forgot')->with(
                'error',
                'Este enlace ha expirado o ya fue utilizado. Solicita uno nuevo.'
            );
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    /**
     * POST /password/reset
     * Procesa el cambio de contraseña.
     */
    public function resetPassword()
    {
        $token           = trim((string)$this->request->getPost('token'));
        $password        = (string)$this->request->getPost('password');
        $passwordConfirm = (string)$this->request->getPost('password_confirm');

        // Validar formato del token
        if (empty($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            return redirect()->to('/password/forgot')->with(
                'error',
                'El enlace no es válido. Solicita uno nuevo.'
            );
        }

        // Validar contraseña
        if (empty($password)) {
            return redirect()->back()->with('error', 'La contraseña es obligatoria.');
        }

        if (mb_strlen($password) < self::MIN_PASSWORD_LENGTH) {
            return redirect()->back()
                ->with('error', 'La contraseña debe tener al menos ' . self::MIN_PASSWORD_LENGTH . ' caracteres.')
                ->with('token', $token);
        }

        if (mb_strlen($password) > self::MAX_PASSWORD_LENGTH) {
            return redirect()->back()
                ->with('error', 'La contraseña no puede tener más de ' . self::MAX_PASSWORD_LENGTH . ' caracteres.')
                ->with('token', $token);
        }

        if ($password !== $passwordConfirm) {
            return redirect()->back()
                ->with('error', 'Las contraseñas no coinciden.')
                ->with('token', $token);
        }

        // Ejecutar el cambio
        $service = new PasswordResetService();
        $result = $service->resetPassword($token, $password);

        if (!$result) {
            return redirect()->to('/password/forgot')->with(
                'error',
                'Este enlace ha expirado o ya fue utilizado. Solicita uno nuevo.'
            );
        }

        // Éxito: redirigir al login con mensaje
        return redirect()->to('/login')->with(
            'success',
            'Tu contraseña ha sido restablecida exitosamente. Inicia sesión con tu nueva contraseña.'
        );
    }
}
