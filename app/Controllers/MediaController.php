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

        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $video_exts = ["mp4", "mov", "webm", "avi"];
        if (in_array($ext, $video_exts)) {
            return $this->video(...$segments);
        }

        $mime = mime_content_type($fullPath);
        if (empty($mime) || strpos($mime, "image/") !== 0) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $valid_exts = [
                "jpg" => "image/jpeg", 
                "jpeg" => "image/jpeg", 
                "png" => "image/png", 
                "gif" => "image/gif", 
                "webp" => "image/webp", 
                "svg" => "image/svg+xml",
                "heic" => "image/heic",
                "heif" => "image/heif"
            ];
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
        
        $start = 0;
        $end = $filesize - 1;
        
        // Headers obligatorios para iOS AVPlayer
        header('Content-Type: ' . $mime);
        header('Accept-Ranges: bytes');
        header('Cache-Control: public, max-age=604800');
        header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $m)) {
                $start = intval($m[1]);
                $end = ($m[2] === '') ? $filesize - 1 : intval($m[2]);
            }

            if ($start > $end || $start >= $filesize) {
                http_response_code(416);
                header("Content-Range: bytes */$filesize");
                exit;
            }

            http_response_code(206);
            header("Content-Range: bytes $start-$end/$filesize");
        } else {
            http_response_code(200);
        }

        $length = $end - $start + 1;
        header("Content-Length: $length"); // ← ESTE es el que iOS exige

        // Stream en chunks
        @ob_end_clean();
        $fp = fopen($fullPath, 'rb');
        if ($fp !== false) {
            fseek($fp, $start);
            $buffer = 8192;
            $remaining = $length;
            while ($remaining > 0 && !feof($fp) && connection_status() === CONNECTION_NORMAL) {
                $read = min($buffer, $remaining);
                echo fread($fp, $read);
                flush();
                $remaining -= $read;
            }
            fclose($fp);
        }
        exit;
    }

    }
