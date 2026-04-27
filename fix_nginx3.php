<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'location ~* ^(?!\/(api|admin|media)\/).*\.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {',
    'location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    # Skip interception for CodeIgniter routes
    if ($request_uri ~* "^/(api|admin|media)/") {
        rewrite ^/(.*)$ /index.php/$1 last;
    }',
    $content
);
file_put_contents($file, $content);
