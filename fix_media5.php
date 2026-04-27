<?php
$file = 'app/Controllers/MediaController.php';
$content = file_get_contents($file);

$content = str_replace(
    '$dirs = ["staff", "announcements", "avatars", "vehicles", "access", "payments"];',
    '$dirs = ["staff", "announcements", "avatars", "vehicles", "access", "payments", "condominiums", "condominiums/1", "condominiums/2"];',
    $content
);
file_put_contents($file, $content);
