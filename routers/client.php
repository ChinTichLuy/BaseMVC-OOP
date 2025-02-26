<?php

use App\Controllers\Client\AboutController;
use App\Controllers\Client\HomeController;
use App\Controllers\Client\DetailController;

$router->get('/', HomeController::class . '@index');
$router->get('/detail/{$id}', DetailController::class . '@detail');
$router->get('/about', AboutController::class . '@index');