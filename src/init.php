<?php

use models\BaseModel;

const VIEWS = 'src/views';

$router = new \Klein\Klein();
$redis = new \Redis();
$redis->connect('127.0.0.1', 6379);

$session = new helpers\RedisSessionHandler($redis);
session_set_save_handler($session);
session_start();

$route = function ($name, $action) use ($redis, $session) {
    $classname = "controllers\\{$name}Controller";
    return function ($request, $response, $service, $app) use ($classname, $action, $redis, $session) {
        //$app->redis = $redis;
        $app->session = $session;
        $app->model = new BaseModel($redis);
        $c = new $classname($request, $response, $service, $app);
        $c->{$action . 'Action'}();
    };
};


