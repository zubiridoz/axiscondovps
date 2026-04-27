<?php
$file = 'app/Controllers/MediaController.php';
$content = file_get_contents($file);
$content = str_replace(
    '        // Validar que se proporcionó al menos un segmento
        if (empty($segments)) {
            // Auto-detectar carpeta si no viene
if (count($segments) === 1) {',
    '        // If string comes as a single argument containing slashes (because of regex (.*))
        if (count($segments) === 1 && strpos($segments[0], "/") !== false) {
            $segments = explode("/", $segments[0]);
        }

        // Auto-detectar carpeta si no viene
if (count($segments) === 1) {',
    $content
);
file_put_contents($file, $content);
