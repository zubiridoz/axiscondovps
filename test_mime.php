<?php
echo "TEST!\n";
$fullPath = '/home/axiscondo-app/htdocs/app.axiscondo.mx/writable/uploads/announcements/ann_69eee8ac12e39_1777264812.jpg';
echo "is_file: " . (is_file($fullPath) ? 'yes' : 'no') . "\n";
$mime = mime_content_type($fullPath);
echo "mime: $mime\n";
