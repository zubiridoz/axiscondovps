<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    '  location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    # Skip interception for CodeIgniter routes
    if ($request_uri ~* "^/(api|admin|media)/") {
        return 404; # Let PHP handle it, handled by the try_files below... wait this block catches it
    }
    expires max;
    access_log off;
    log_not_found off;
  }',
    '  location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    # Evitar capturar si la ruta incluye /api/, /admin/ o /media/
    if ($request_uri ~* "^/(api|admin|media)/") {
        rewrite ^ /index.php last;
    }
    expires max;
    access_log off;
    log_not_found off;
  }',
    $content
);
file_put_contents($file, $content);
