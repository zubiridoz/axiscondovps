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

    /**
     * Servir archivos de video con soporte para HTTP 206 Range Requests
     * 
     * Esto permite que Flutter video_player pueda hacer streaming efectivo
     * al soportar "byte-range requests" (descarga en segmentos).
     * 
     * Rutas:
     * - /media/video/announcements/video.mp4
     * - /media/video/tickets/comment_video.mov
     * 
     * @param string ...$segments Segmentos de la ruta del archivo
     * @return mixed
     */
    public function video(string ...$segments)
    {
        if (count($segments) === 1 && strpos($segments[0], "/") !== false) {
            $segments = explode("/", $segments[0]);
        }

        if (empty($segments)) {
            return $this->response->setStatusCode(404);
        }

        $filename = implode("/", $segments);
        $filename = str_replace(["..", "\\"], "", $filename);
        
        $fullPath = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $filename;
        
        $realPath = realpath(dirname($fullPath));
        $uploadsPath = realpath(WRITEPATH . "uploads");
        
        if ($realPath === false || strpos($realPath, $uploadsPath) !== 0 || !is_file($fullPath)) {
            return $this->response->setStatusCode(404);
        }

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $valid_exts = ["mp4" => "video/mp4", "mov" => "video/quicktime", "webm" => "video/webm", "avi" => "video/x-msvideo"];
        
        if (!array_key_exists($ext, $valid_exts)) {
            return $this->response->setStatusCode(403);
        }
        
        $mime = $valid_exts[$ext];
        $filesize = filesize($fullPath);
        
        // Manejo de HTTP 206 Range Requests para streaming
        $this->response->setHeader("Accept-Ranges", "bytes");
        $this->response->setHeader("Content-Type", $mime);
        $this->response->setHeader("Content-Disposition", "inline; filename=\"" . basename($fullPath) . "\"");
        $this->response->setHeader("Cache-Control", "public, max-age=604800");
        
        $start = 0;
        $end = $filesize - 1;
        $status = 200;
        
        // Procesar Range Request (HTTP 206)
        if ($this->request->getServer('HTTP_RANGE')) {
            $range = $this->request->getServer('HTTP_RANGE');
            
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                $end = ($matches[2] === '') ? $filesize - 1 : intval($matches[2]);
                
                if ($start > $end || $start >= $filesize) {
                    $this->response->setStatusCode(416);
                    $this->response->setHeader("Content-Range", "bytes */" . $filesize);
                    return $this->response;
                }
                
                $status = 206;
                $length = $end - $start + 1;
                
                $this->response->setHeader("Content-Range", "bytes " . $start . "-" . $end . "/" . $filesize);
                $this->response->setHeader("Content-Length", (string) $length);
            }
        }
        
        if ($status === 200) {
            $this->response->setHeader("Content-Length", (string) $filesize);
        }
        
        $this->response->setStatusCode($status);
        
        // Enviar el archivo (o rango solicitado)
        $fp = fopen($fullPath, 'rb');
        if ($fp === false) {
            return $this->response->setStatusCode(500);
        }
        
        if ($start > 0) {
            fseek($fp, $start);
        }
        
        $chunkSize = 1024 * 1024; // 1MB chunks
        $remainingBytes = $end - $start + 1;
        
        while ($remainingBytes > 0) {
            $readSize = min($chunkSize, $remainingBytes);
            $chunk = fread($fp, $readSize);
            if ($chunk === false || $chunk === '') {
                break;
            }
            echo $chunk;
            $remainingBytes -= strlen($chunk);
        }
        
        fclose($fp);
        
        // Enviar respuesta sin body adicional (ya enviamos el contenido)
        return $this->response;
    }

    }
