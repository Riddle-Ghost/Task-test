<?php

return $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute(['GET'], '/', ['App\Controllers\TasksController', 'index']);

    $r->addRoute(['GET'], '/login', ['App\Controllers\LoginController', 'index']);
    $r->addRoute(['POST'], '/login', ['App\Controllers\LoginController', 'login']);
    $r->addRoute(['GET'], '/logout', ['App\Controllers\LoginController', 'logout']);
    $r->addRoute(['GET'], '/register', ['App\Controllers\RegisterController', 'index']);
    $r->addRoute(['POST'], '/register', ['App\Controllers\RegisterController', 'register']);


    $r->addRoute(['GET'], '/tasks/add', ['App\Controllers\TasksController', 'create']);
    $r->addRoute(['POST'], '/tasks/store', ['App\Controllers\TasksController', 'store']);
    $r->addRoute(['GET'], '/tasks/edit', ['App\Controllers\TasksController', 'edit']);
    $r->addRoute(['POST'], '/tasks/update', ['App\Controllers\TasksController', 'update']);

});