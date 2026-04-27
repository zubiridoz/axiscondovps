<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'if ($request_uri ~* "^/(api|admin|media)/") {
        rewrite ^/(.*)$ /index.php/$1 last;
    }',
    'if ($request_uri ~* "^/(api|admin|media)/") {
        rewrite ^/(.*)$ /index.php/$1 break;
    }',
    $content
);
file_put_contents($file, $content);
