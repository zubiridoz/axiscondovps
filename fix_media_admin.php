<?php
$file = 'app/Controllers/Admin/MediaController.php';
$content = file_get_contents($file);
$content = str_replace(
    '        if (empty($segments)) {
            if (count($segments) === 1) {',
    '        if (count($segments) === 1 && strpos($segments[0], "/") !== false) {
            $segments = explode("/", $segments[0]);
        }
        if (empty($segments)) {',
    $content
);
file_put_contents($file, $content);
