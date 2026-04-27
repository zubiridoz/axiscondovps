<?php
$file = 'app/Controllers/MediaController.php';
$content = file_get_contents($file);

// Find the image method and replace its entire body safely
$start = strpos($content, 'public function image(string ...$segments)');

// find end of image method by finding start of next method or end of class
$end = strpos($content, 'public function serveFile(', $start);
if ($end === false) {
    $end = strpos($content, 'public function ', $start + 50);
}
if ($end === false) {
    $end = strrpos($content, '}'); // end of class
}

$newMethod = 'public function image(string ...$segments)
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
                $segments = ["staff", $segments[0]];
            }
        }

        $filename = implode("/", $segments);
        $filename = str_replace(["..", "\\\\"], "", $filename);
        
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

    ';

$content = substr_replace($content, $newMethod, $start, $end - $start);
// fix broken syntax if we accidentally overwrote a bit of the next function declaration
$content = preg_replace('/\}\s*\n\s*\.\.\.\$segments\)\s*\{/', '}', $content);

file_put_contents($file, $content);
