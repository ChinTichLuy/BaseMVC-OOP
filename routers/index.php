<?php

use App\Controllers\ProductController;
use Bramus\Router\Router;

// Create Router instance
$router = new Router();

// Define routes

$router->get('/',function(){
    echo 'Ph49014';
});

$router->get('/products',           ProductController::class .'@index');
$router->get('/products/{id}/show', ProductController::class .'@show');
$router->get('/products/create',    ProductController::class .'@create');
$router->post('/products/store',    ProductController::class .'@store');
$router->get('/products/{id}/edit', ProductController::class .'@edit');
$router->post('/products/{id}/update',ProductController::class .'@update');
$router->get('/products/{id}/delete', ProductController::class .'@delete');

// Run it!
$router->run();