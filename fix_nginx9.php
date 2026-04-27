<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    '  location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    # Skip interception for CodeIgniter routes
    if ($request_uri ~* "^/(api|admin|media|configuracion)/") {
        rewrite ^/(.*)$ /index.php/$1 break;
    }
    expires max;
    access_log off;
    log_not_found off;
  }',
    '  location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    # Si la URL real pide un archivo que existe, lo sirve
    try_files $uri @ci;
    expires max;
    access_log off;
    log_not_found off;
  }
  
  location @ci {
    rewrite ^/(.*)$ /index.php/$1 last;
  }',
    $content
);
file_put_contents($file, $content);
