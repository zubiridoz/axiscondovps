<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * AssetService - Servicio centralizado para gestionar assets (imágenes, documentos, etc)
 * 
 * Características:
 * - Multi-tenant safe (previene directory traversal)
 * - CDN-ready (URLs versionadas)
 * - MIME type detection
 * - Soft delete compatible
 * - Fácil migración a S3/Cloud Storage
 * 
 * Uso:
 * $url = AssetService::getUrl('condominiums', '123', 'cover_123.jpg');
 * // → /api/v1/assets/condominiums/123/cover_123.jpg
 */
class AssetService
{
    // Tipos de assets soportados
    public const TYPES = [
        'condominiums'     => 'condominiums',      // Logos, covers de condominios
        'amenities'        => 'amenities',         // Imágenes de amenidades
        'avatars'          => 'avatars',           // Avatares de usuarios
        'tickets'          => 'tickets',           // Archivos de tickets
        'announcements'    => 'announcements',     // Documentos de anuncios
        'documents'        => 'documents',         // Documentos generales
        'financial'        => 'financial',         // Comprobantes de pago
        'payments'         => 'payments',          // Pruebas de pago
    ];

    private const STORAGE_ROOT = WRITEPATH . 'uploads';

    /**
     * Obtiene la ruta segura del archivo (validado, sin traversal)
     */
    public static function getPath(string $type, ?string $subDir, string $filename): string
    {
        if (!isset(self::TYPES[$type])) {
            throw new \InvalidArgumentException("Asset type inválido: {$type}");
        }

        // Construir ruta
        $path = self::STORAGE_ROOT . DIRECTORY_SEPARATOR . self::TYPES[$type];
        if ($subDir) {
            $path .= DIRECTORY_SEPARATOR . $subDir;
        }
        $path .= DIRECTORY_SEPARATOR . $filename;

        // Validar que no hay directory traversal
        $realPath = realpath($path);
        $realBase = realpath(self::STORAGE_ROOT . DIRECTORY_SEPARATOR . self::TYPES[$type]);

        if (!$realPath || strpos($realPath, $realBase) !== 0 || !file_exists($realPath)) {
            throw new \RuntimeException("Acceso denegado o archivo no encontrado: {$filename}");
        }

        return $realPath;
    }

    /**
     * Obtiene URL pública del asset (segura, versionada)
     * 
     * Ejemplo:
     * - AssetService::getUrl('condominiums', '2', 'cover_123.jpg')
     *   → https://app.axiscondo.mx/api/v1/assets/condominiums/2/cover_123.jpg
     */
    public static function getUrl(string $type, ?string $subDir, string $filename): string
    {
        $pathSegments = ['api', 'v1', 'assets', self::TYPES[$type]];
        
        if ($subDir) {
            $pathSegments[] = $subDir;
        }
        
        $pathSegments[] = $filename;
        $path = implode('/', $pathSegments);

        return base_url($path);
    }

    /**
     * Upload seguro de archivo
     * 
     * Valida:
     * - Archivo válido y no movido
     * - Extensión permitida
     * - Crea directorio si no existe
     * - Genera nombre único
     */
    public static function upload(
        string $type,
        UploadedFile $file,
        ?string $subDir = null,
        array $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf']
    ): string {
        if (!isset(self::TYPES[$type])) {
            throw new \InvalidArgumentException("Asset type inválido: {$type}");
        }

        if (!$file->isValid() || $file->hasMoved()) {
            throw new \RuntimeException("Archivo inválido o ya fue movido");
        }

        $ext = strtolower($file->getExtension());
        if (!in_array($ext, $allowedExtensions)) {
            throw new \RuntimeException("Extensión no permitida: {$ext}");
        }

        // Construir directorio
        $uploadDir = self::STORAGE_ROOT . DIRECTORY_SEPARATOR . self::TYPES[$type];
        if ($subDir) {
            $uploadDir .= DIRECTORY_SEPARATOR . $subDir;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generar nombre único (hex + timestamp)
        $newName = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ext;
        $file->move($uploadDir, $newName);

        return $newName;
    }

    /**
     * Delete seguro de archivo
     */
    public static function delete(string $type, ?string $subDir, string $filename): bool
    {
        try {
            $path = self::getPath($type, $subDir, $filename);
            if (file_exists($path) && is_file($path)) {
                return unlink($path);
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtiene información del archivo (size, mime, mtime)
     */
    public static function getInfo(string $type, ?string $subDir, string $filename): array
    {
        $path = self::getPath($type, $subDir, $filename);

        return [
            'path'      => $path,
            'size'      => filesize($path),
            'mime'      => mime_content_type($path),
            'mtime'     => filemtime($path),
            'url'       => self::getUrl($type, $subDir, $filename),
        ];
    }

    /**
     * Sirve archivo con headers inteligentes (Cache, ETag, Last-Modified)
     * 
     * Retorna response object para usar directamente en controllers:
     * return AssetService::serve($type, $subDir, $filename);
     */
    public static function serve($type, $subDir, $filename)
    {
        $path = self::getPath($type, $subDir, $filename);
        $mtime = filemtime($path);
        $etag = md5($path . $mtime);

        $response = service('response');
        $request = service('request');

        // ETag check (304 Not Modified)
        if ($request->getHeaderLine('If-None-Match') === $etag) {
            return $response->setStatusCode(304);
        }

        // Last-Modified check
        $lastModified = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
        if ($request->getHeaderLine('If-Modified-Since') === $lastModified) {
            return $response->setStatusCode(304);
        }

        // Headers de cache AGRESIVO (1 año - assets inmutables)
        $response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $response->setHeader('ETag', $etag);
        $response->setHeader('Last-Modified', $lastModified);
        $response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        $response->setHeader('Vary', 'Accept-Encoding');

        // CORS headers (para apps móviles)
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');

        return $response
            ->setContentType(mime_content_type($path))
            ->setBody(file_get_contents($path));
    }

    /**
     * Batch delete (para soft deletes de registros)
     */
    public static function deleteMultiple(array $files): array
    {
        $results = [];
        foreach ($files as $file) {
            $key = "{$file['type']}/{$file['subDir']}/{$file['filename']}";
            $results[$key] = self::delete($file['type'], $file['subDir'] ?? null, $file['filename']);
        }
        return $results;
    }
}
