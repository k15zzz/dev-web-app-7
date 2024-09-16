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

            $r->addRoute('GET', '/search-producer', '\App\Http\Controllers\SearchController@searchProducerPage');
            $r->addRoute('POST', '/search-producer', '\App\Http\Controllers\SearchController@searchProducer');

            $r->addRoute('GET', '/search-capacity', '\App\Http\Controllers\SearchController@searchCapacityPage');
            $r->addRoute('POST', '/search-capacity', '\App\Http\Controllers\SearchController@searchCapacity');

            $r->addRoute('GET', '/auth', '\App\Http\Controllers\AuthController@authPage');
            $r->addRoute('POST', '/auth', '\App\Http\Controllers\AuthController@auth');

            $r->addRoute('GET', '/logout', '\App\Http\Controllers\AuthController@logout');
        });
    }
}