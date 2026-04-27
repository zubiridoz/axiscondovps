<?php

namespace App\Services\Auth;

use App\Models\Tenant\PersonalAccessTokenModel;

/**
 * TokenService
 * 
 * Genera y valida tokens para PWA o llamadas de API.
 * Nunca guarda el token en plano en la base de datos, siempre usa SHA256.
 */
class TokenService
{
    /**
     * Crea un token plano para el cliente y guarda su versión hasheada en la DB.
     * @return string El token en texto plano (que solo se mostrará esta vez).
     */
    public function createApiToken(int $userId, string $deviceName = 'PWA Device'): string
    {
        // 1. Generamos un token aleatorio criptográficamente seguro
        $plainToken = bin2hex(random_bytes(32)); 
        
        // 2. Lo hasheamos aplicando SHA256
        $hashedToken = hash('sha256', $plainToken);
        
        // 3. Lo guardamos en BD
        $model = new PersonalAccessTokenModel();
        
        // Fijamos expiración a 1 año, por ejemplo
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 year'));
        
        $model->insert([
            'user_id'      => $userId,
            'token_hash'   => $hashedToken,
            'device_name'  => $deviceName,
            'expires_at'   => $expiresAt
        ]);
        
        // Retornamos el token plano para que la PWA lo guarde en LocalStorage
        return $plainToken;
    }

    /**
     * Valida un token plano recibido de un Bearer Header.
     * Retorna el objeto del token (que incluye el user_id) si es válido.
     */
    public function validateToken(string $plainToken): ?array
    {
        $hashedToken = hash('sha256', $plainToken);
        
        $model = new PersonalAccessTokenModel();
        $tokenData = $model->where('token_hash', $hashedToken)->first();
        
        if (!$tokenData) {
            return null; // Token no existe
        }
        
        // Comprobamos si no ha expirado
        if ($tokenData['expires_at'] && strtotime($tokenData['expires_at']) < time()) {
            return null; // Expiró
        }
        
        // Actualizamos last_used_at
        $model->update($tokenData['id'], ['last_used_at' => date('Y-m-d H:i:s')]);
        
        return $tokenData;
    }
    
    /**
     * Revoca un token específico (Logout de API)
     */
    public function revokeToken(string $plainToken): bool
    {
        $hashedToken = hash('sha256', $plainToken);
        $model = new PersonalAccessTokenModel();
        // Delete físico ya que la tabla no usa softDeletes
        $model->where('token_hash', $hashedToken)->delete();
        
        return true;
    }

    /**
     * Revoca TODOS los tokens activos de un usuario.
     * Útil cuando se le remueve de su última comunidad o por seguridad.
     */
    public function revokeAllUserTokens(int $userId): bool
    {
        $model = new PersonalAccessTokenModel();
        $model->where('user_id', $userId)->delete();
        return true;
    }
}
