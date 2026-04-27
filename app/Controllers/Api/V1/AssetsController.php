<?php

namespace App\Controllers\Api\V1;

use App\Services\AssetService;
use CodeIgniter\RESTful\ResourceController;

/**
 * AssetsController
 * 
 * Endpoint: GET /api/v1/assets/{type}/{subDir}/{filename}
 * 
 * Ejemplo de URLs (públicas, sin auth):
 * - https://app.axiscondo.mx/api/v1/assets/condominiums/2/cover_xyz.jpg
 * - https://app.axiscondo.mx/api/v1/assets/amenities/1/image_abc.jpg
 * - https://app.axiscondo.mx/api/v1/assets/avatars/user_123.jpg
 * 
 * Características:
 * - Cache headers inteligentes (ETag, 304 Not Modified)
 * - CORS permitido (para apps móviles)
 * - Prevención de directory traversal
 * - Error handling robusto
 */
class AssetsController extends ResourceController
{
    /**
     * GET /api/v1/assets/{type}/{subDir}/{filename}
     * 
     * @param string $type       Tipo de asset (condominiums, amenities, avatars, etc)
     * @param string $subDir     Subdirectorio (tenant ID, amenity ID, etc)
     * @param string $filename   Nombre de archivo
     * 
     * @return Response Archivo con headers de cache inteligentes
     */
    public function serve(string $type, string $subDir, string $filename)
    {
        try {
            return AssetService::serve($type, $subDir, $filename);
        } catch (\RuntimeException $e) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Asset no encontrado']);
        } catch (\Exception $e) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Error al servir el archivo']);
        }
    }

    /**
     * GET /api/v1/assets/info/{type}/{subDir}/{filename}
     * 
     * Retorna metadata del archivo (tamaño, MIME type, etc)
     * Útil para validaciones en cliente
     */
    public function info(string $type, string $subDir, string $filename)
    {
        try {
            $info = AssetService::getInfo($type, $subDir, $filename);
            return $this->response->setJSON($info);
        } catch (\Exception $e) {
            return $this->failNotFound('Asset no encontrado');
        }
    }
}
