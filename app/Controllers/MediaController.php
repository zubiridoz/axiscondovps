<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * MediaController - Servidor centralizado de medios desde writable/uploads
 * 
 * Este controlador sirve TODAS las imágenes de la aplicación desde writable/uploads
 * Soporta subdirectorios y proporciona caché HTTP eficiente.
 * 
 * Rutas:
 * - /media/image/amenities/photo.jpg  → writable/uploads/amenities/photo.jpg
 * - /media/image/announcements/img.jpg → writable/uploads/announcements/img.jpg
 * - /media/image/packages/pkg.jpg      → writable/uploads/packages/pkg.jpg
 * - /media/image/profiles/user.jpg     → writable/uploads/profiles/user.jpg
 * - /media/image/settings/logo.jpg     → writable/uploads/settings/logo.jpg
 */
class MediaController extends Controller
{
    /**
     * Servir archivos de medios desde writable/uploads
     * 
     * Acepta rutas anidadas:
     * - /media/image/amenities/photo.jpg
     * - /media/image/amenities/1/photo.jpg (con subdirectorio)
     * - /api/v1/amenities/image/photo.jpg (legacy)
     * 
     * @param string ...$segments Segmentos de la ruta del archivo
     * @return mixed
     */
    public function image(string ...$segments)
    {
        if (count($segments) === 1 && strpos($segments[0], "/") !== false) {
            $segments = explode("/", $segments[0]);
        }

        if (empty($segments)) {
            return $this->response->setStatusCode(404);
        }

        if (count($segments) === 1) {
            $file = $segments[0];
            $dirs = ["staff", "announcements", "avatars", "vehicles", "access", "payments"];
            $found = false;
            foreach ($dirs as $dir) {
                if (is_file(WRITEPATH . "uploads/" . $dir . "/" . $file)) {
                    $segments = [$dir, $file];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Auto-detect by searching the file recursively inside uploads/
                try {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(WRITEPATH . "uploads/"));
                    foreach ($iterator as $info) {
                        if ($info->isFile() && $info->getFilename() === $file) {
                            $segments = explode("/", str_replace(WRITEPATH . "uploads/", "", str_replace("\\", "/", $info->getPathname())));
                            $found = true;
                            break;
                        }
                    }
                } catch (\Exception $e) {}
            }
            if (!$found) {
                $segments = ["staff", $segments[0]];
            }
        }

        $filename = implode("/", $segments);
        $filename = str_replace(["..", "\\"], "", $filename);
        
        $fullPath = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $filename;
        
        $realPath = realpath(dirname($fullPath));
        $uploadsPath = realpath(WRITEPATH . "uploads");
        
        if ($realPath === false || strpos($realPath, $uploadsPath) !== 0 || !is_file($fullPath)) {
            return $this->response->setStatusCode(404);
        }

        $mime = mime_content_type($fullPath);
        if (empty($mime) || strpos($mime, "image/") !== 0) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $valid_exts = ["jpg" => "image/jpeg", "jpeg" => "image/jpeg", "png" => "image/png", "gif" => "image/gif", "webp" => "image/webp", "svg" => "image/svg+xml"];
            if (array_key_exists($ext, $valid_exts)) {
                $mime = $valid_exts[$ext];
            } else {
                $mime = "application/octet-stream";
            }
        }

        $cacheTime = 604800; // 7 dias
        $this->response->setHeader("Cache-Control", "public, max-age=" . $cacheTime)
                       ->setHeader("Expires", gmdate("D, d M Y H:i:s", time() + $cacheTime) . " GMT")
                       ->setHeader("Pragma", "cache")
                       ->setHeader("Content-Type", $mime)
                       ->setHeader("Content-Disposition", "inline; filename=\"" . basename($fullPath) . "\"")
                       ->setBody(file_get_contents($fullPath));

        return $this->response;
    }

    }
