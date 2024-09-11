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
            $r->addRoute('GET', '/', function () {
                echo 'Welcome to the home page!';
            });

            $r->addRoute('GET', '/test', '\App\Http\Controllers\TestController@showView');
            $r->addRoute('GET', '/test2', '\App\Http\Controllers\TestController@show');
        });
    }
}