<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'location / {',
    'location ~* ^/(admin/.*|api/v1/.*|media/image/.*|tickets/media/.*|amenidades/.*|anuncios/.*|archivo/.*|configuracion/.*|avatar/.*)\.(jpg|jpeg|png|gif|svg|pdf)$ {
    try_files $uri /index.php?$query_string;
  }

  location / {',
    str_replace(
        'location ~* ^/(api|admin|media|configuracion|tickets|anuncios|amenidades|archivo)/.*\.(jpg|jpeg|png|gif|svg)$ {
    try_files $uri $uri/ /index.php?$query_string;
  }',
        '',
        $content
    )
);
file_put_contents($file, $content);
