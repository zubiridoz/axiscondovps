<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Services\Auth\LoginService;
use App\Services\Auth\TokenService;

class ApiAuthController extends ResourceController
{
    /**
     * Autenticación PWA (No usa cookies ni redirecciones, devuelve JSON y Bearer Token)
     */
    public function login()
    {
        $email      = $this->request->getVar('email');
        $password   = $this->request->getVar('password');
        $deviceName = $this->request->getVar('device_name') ?? 'Generic PWA Device';
        
        $loginService = new LoginService();
        
        try {
            $user = $loginService->validateCredentials($email, $password);
            
            if (!$user) {
                return $this->failUnauthorized('Credenciales incorrectas');
            }
            
            // Residentes usualmente están en 1 solo tenant (que es su residencia)
            $tenantRoles = $loginService->getUserTenants($user['id']);
            
            if (empty($tenantRoles)) {
                return $this->failForbidden('Usuario sin condominios asignados');
            }
            
            // Creamos un ApiToken plano en lugar de Sesión Web
            $tokenService = new TokenService();
            $plainToken   = $tokenService->createApiToken($user['id'], $deviceName);
            
            // Extraemos el rol primario para Flutter
            $primaryRole = !empty($tenantRoles) && isset($tenantRoles[0]['role_name']) 
                ? $tenantRoles[0]['role_name'] 
                : 'guard';

            // Obtener datos del condominio y unidad principal para Flutter
            $condoName = 'Mi Condominio';
            $unitNumber = 'Sin unidad';
            if (!empty($tenantRoles)) {
                $condoId = $tenantRoles[0]['condominium_id'];
                $db = \Config\Database::connect();
                $condo = $db->table('condominiums')->select('name')->where('id', $condoId)->get()->getRowArray();
                if ($condo) $condoName = $condo['name'];
                
                $resident = $db->table('residents')->where('user_id', $user['id'])->where('condominium_id', $condoId)->get()->getRowArray();
                if ($resident && !empty($resident['unit_id'])) {
                    $unit = $db->table('units')->select('unit_number')->where('id', $resident['unit_id'])->get()->getRowArray();
                    if ($unit) $unitNumber = $unit['unit_number'];
                }
            }

            return $this->respond([
                'status'  => 'success', // Esperado por Flutter
                'code'    => 200,
                'message' => 'Login exitoso',
                'role'    => $primaryRole, // Esperado por Flutter
                'data'    => [
                    'user'    => [
                        'id'         => $user['id'],
                        'first_name' => $user['first_name'],
                        'last_name'  => $user['last_name'],
                        'email'      => $user['email'],
                        'phone'      => $user['phone'] ?? '',
                        'avatar'     => $user['avatar'] ?? '',
                        'condo_name'  => $condoName,
                        'unit_number' => $unitNumber,
                        'unit_id'     => ($resident && !empty($resident['unit_id'])) ? (int) $resident['unit_id'] : null,
                    ],
                    'role'    => $primaryRole,
                    'tenants' => $tenantRoles, // Para que la App Móvil sepa a qué condominios saltar
                    'current_condo_id' => $condoId ?? null, // 🔥 REQUERIDO POR FLUTTER PARA MANDAR X-Condo-Id
                    'token'   => $plainToken
                ]
            ]);
            
        } catch (\Exception $e) {
            return $this->fail($e->getMessage());
        }
    }

    /**
     * Invalidación de Bearer Token
     */
    public function logout()
    {
        $authHeader = $this->request->getHeaderLine('Authorization');
        
        if (strpos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
            
            $tokenService = new TokenService();
            $tokenService->revokeToken($token);
            
            return $this->respondDeleted(['message' => 'Token revocado exitosamente']);
        }
        
        return $this->failUnauthorized('Require Bearer Token');
    }
}
