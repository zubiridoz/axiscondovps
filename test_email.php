<?php
define('FCPATH', __DIR__ . '/public' . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$email = \Config\Services::email();
$email->setFrom('soporte@didecommx.com', 'AxisCondo Test');
$email->setTo('soporte@didecommx.com'); // Test sending to itself
$email->setSubject('Test Email Config');
$email->setMessage('This is a test message to verify SMTP config.');

if ($email->send()) {
    echo "SUCCESS\n";
} else {
    echo "FAILED\n";
    echo $email->printDebugger(['headers']);
}
