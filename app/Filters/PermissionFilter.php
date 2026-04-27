<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\Auth\PermissionService;

/**
 * PermissionFilter
 * 
 * Middleware abstracto RBAC. Verifica si la matriz de sesión cargada con 
 * los permisos del Tenant contiene el string específico pasado por parámetro.
 */
class PermissionFilter implements FilterInterface
{
    /**
     * @param array $arguments Ejemplo: ['manage_users'] o ['view_finances', 'delete_payments']
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (empty($arguments)) {
            return; // Si no piden permisos estrictos, lo dejamos pasar asumiendo AuthFilter previo
        }
        
        $permissionService = new PermissionService();
        $hasPermission = false;
        
        // Iteramos los argumentos (Pueden mandarnos OR: si tiene 1 de estos, pasa). Si quieres un AND riguroso
        // habría que cambiar la lógica.
        foreach ($arguments as $permission) {
            if ($permissionService->hasPermission($permission)) {
                $hasPermission = true;
                break;
            }
        }
        
        if (!$hasPermission) {
            if ($request->isAJAX() || strpos($request->getHeaderLine('Accept'), 'application/json') !== false) {
                 return response()->setJSON(['error' => 'No tienes los permisos necesarios para realizar esta acción.'])->setStatusCode(403);
            }
            return redirect()->back()->with('error', 'Acceso denegado. Faltan permisos (403).');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
