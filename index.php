<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/src/init.php';

$router->respond('GET', '/', $route('Home', 'index'));
$router->respond('GET', '/auth/cb/[:provider]/', $route('Home', 'cb'));
$router->respond('GET', '/api/auth/', $route('Home', 'login'));

$router->respond('GET', '/api/comments/', $route('Home', 'getAllComments'));
$router->respond('GET', '/api/comments/[:id]', $route('Home', 'getComments'));

$router->respond('POST', '/write/[:id]', $route('Home', 'write'));
$router->respond('POST', '/auth/in/', $route('Home', 'redirect'));

$router->dispatch();