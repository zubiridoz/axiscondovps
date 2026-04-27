<?php
$file = 'app/Config/Routes.php';
$content = file_get_contents($file);

// Arreglar referencias al MediaController que están en el grupo admin
$content = str_replace(
    "'MediaController::image/announcements/$1'",
    "'\\App\\Controllers\\MediaController::image/announcements/$1'",
    $content
);
$content = str_replace(
    "'MediaController::image/financial/$1'",
    "'\\App\\Controllers\\MediaController::image/financial/$1'",
    $content
);
$content = str_replace(
    "'MediaController::image/payments/$1'",
    "'\\App\\Controllers\\MediaController::image/payments/$1'",
    $content
);
$content = str_replace(
    "'MediaController::image/settings/$1'",
    "'\\App\\Controllers\\MediaController::image/settings/$1'",
    $content
);
$content = str_replace(
    "'MediaController::image/amenities/$1'",
    "'\\App\\Controllers\\MediaController::image/amenities/$1'",
    $content
);

file_put_contents($file, $content);
