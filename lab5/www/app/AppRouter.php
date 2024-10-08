<?php

namespace App;

use Core\Contracts\IRouterDispatcher;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

class AppRouter implements IRouterDispatcher
{
    public static function call(): Dispatcher
    {
        return simpleDispatcher(function (RouteCollector $r) {

            $r->addRoute('GET', '/', '\App\Http\Controllers\IndexController@renderPage');
            $r->addRoute('POST', '/api/post/calculate-free', '\App\Http\Controllers\PostController@calculateFree');
        });
    }
}