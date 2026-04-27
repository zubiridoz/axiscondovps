<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'location / {',
    'location ~* ^/(api|admin|media|configuracion|tickets|anuncios|amenidades|archivo)/.*\.(jpg|jpeg|png|gif|svg)$ {
    try_files $uri $uri/ /index.php?$query_string;
  }

  location / {',
    $content
);
file_put_contents($file, $content);
