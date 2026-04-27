<?php
$file = 'app/Controllers/Admin/MediaController.php';
$content = file_get_contents($file);

// check if there's any parsing issue in the new method
$start = strpos($content, 'public function image(string ...$segments)');
$end = strrpos($content, '}'); // assuming it's the last method
echo substr($content, $start, $end - $start);
