<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {',
    'location ~* ^(?!\/(api|admin|media)\/).*\.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {',
    $content
);
file_put_contents($file, $content);
