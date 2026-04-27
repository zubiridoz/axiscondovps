<?php

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class MediaController extends Controller
{
    public function image(string ...$segments)
    {
        if (count($segments) === 1 && strpos($segments[0], "/") !== false) {
            $segments = explode("/", $segments[0]);
        }

        if (empty($segments)) {
            return $this->response->setStatusCode(404);
        }

        $filename = implode("/", $segments);
        $filename = str_replace(["..", "\\\\"], "", $filename);
        
        // Fallback robusto
        $fullPath = WRITEPATH . "uploads" . DIRECTORY_SEPARATOR . $filename;
        if (!is_file($fullPath)) {
            if (count($segments) === 1) {
                try {
                    $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(WRITEPATH . "uploads/"));
                    foreach ($iterator as $info) {
                        if ($info->isFile() && $info->getFilename() === $segments[0]) {
                            $fullPath = $info->getPathname();
                            break;
                        }
                    }
                } catch (\Exception $e) {}
            } elseif (count($segments) === 2) {
                try {
                    $dirPath = WRITEPATH . "uploads/" . $segments[0] . "/";
                    if (is_dir($dirPath)) {
                        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath));
                        foreach ($iterator as $info) {
                            if ($info->isFile() && $info->getFilename() === $segments[1]) {
                                $fullPath = $info->getPathname();
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {}
            }
        }
        
        if (!is_file($fullPath)) {
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

        $cacheTime = 604800;
        $this->response->setHeader("Cache-Control", "public, max-age=" . $cacheTime)
                       ->setHeader("Expires", gmdate("D, d M Y H:i:s", time() + $cacheTime) . " GMT")
                       ->setHeader("Pragma", "cache")
                       ->setHeader("Content-Type", $mime)
                       ->setHeader("Content-Disposition", "inline; filename=\"" . basename($fullPath) . "\"")
                       ->setBody(file_get_contents($fullPath));

        return $this->response;
    }
}
