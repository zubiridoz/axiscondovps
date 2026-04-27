<?php
$file = '/etc/nginx/sites-enabled/app.axiscondo.mx.conf';
$content = file_get_contents($file);
$content = str_replace(
    'include fastcgi.conf;',
    "include fastcgi_params;\n    fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;",
    $content
);
file_put_contents($file, $content);
