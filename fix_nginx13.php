<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);

// Limpiar el segundo location duplicado si existe
$content = preg_replace('/location \\~\* \\\^\/\(admin\/\\.\*\|api\/v1\/\\.\*\|media\/image\/\\.\*\|tickets\/media\/\\.\*\|amenidades\/\\.\*\|anuncios\/\\.\*\|archivo\/\\.\*\|configuracion\/\\.\*\|avatar\/\\.\*\)\\\.\\\(jpg\|jpeg\|png\|gif\|svg\|pdf\\\)\\\$ \{(.*?)\}/s', '', $content, 1);

// Reescribir para capturar todas las imagenes de CI
$content = str_replace(
    'location / {
    try_files $uri $uri/ /index.php?$query_string;
  }',
    'location ~* ^/(admin|api|media|tickets|amenidades|anuncios|archivo|configuracion|avatar|file)/.*\.(jpg|jpeg|png|gif|svg|pdf)$ {
    try_files $uri /index.php?$query_string;
  }

  location / {
    try_files $uri $uri/ /index.php?$query_string;
  }',
    $content
);

file_put_contents($file, $content);
