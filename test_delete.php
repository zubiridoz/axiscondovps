<?php
define('ENVIRONMENT', 'development');
require 'vendor/autoload.php';
$app = \Config\Services::codeigniter(new \Config\App());
$app->initialize();
$model = new \App\Models\Tenant\PersonalAccessTokenModel();
try {
    $model->where('user_id', 9999)->delete();
    echo "Success\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
