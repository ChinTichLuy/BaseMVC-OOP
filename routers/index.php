<?php 

use Bramus\Router\Router;

$router = new Router();

require 'admin.php';
require 'client.php';
require 'auth.php';

$router->run();

