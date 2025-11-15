<?php
$base = realpath(__DIR__ . "/../../application");
require $base . '/vendor/autoload.php';
require_once $base . '/app/Services/CustomerCategoryService.php';
$reflection = new ReflectionClass(\App\Services\CustomerCategoryService::class);
$method = $reflection->getMethod('splitCellReference');
$method->setAccessible(true);
$service = new \App\Services\CustomerCategoryService();
var_export($method->invoke($service, 'D1'));
