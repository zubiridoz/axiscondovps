<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = preg_replace('/location \\~\\\* \\\\\\.\(css\\|js\\|jpg\\|jpeg\\|gif\\|png\\|ico\\|svg\\|woff\\|woff2\\|ttf\\|eot\\|map\)\\\$ \{.*?\}/s', 
'location ~* \.(css|js|jpg|jpeg|gif|png|ico|svg|woff|woff2|ttf|eot|map)$ {
    expires max;
    access_log off;
    log_not_found off;
}', $content);
file_put_contents($file, $content);
