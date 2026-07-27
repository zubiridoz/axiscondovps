<?php
require 'vendor/autoload.php';

$app = require 'system/bootstrap.php';

$tenantService = \App\Services\TenantService::getInstance();
$tenantService->setTenantId(31);

$controller = new \App\Controllers\Api\V1\SecurityController();
$controller->initController(service('request'), service('response'), service('logger'));

$result = $controller->offlineCache();
echo json_encode($result->getBody());
