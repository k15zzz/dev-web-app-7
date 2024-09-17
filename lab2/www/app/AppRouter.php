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

            $r->addRoute('GET', '/searchByRubricAndDate', '\App\Http\Controllers\ArticleController@searchByRubricAndDatePage');
            $r->addRoute('POST', '/searchByRubricAndDate', '\App\Http\Controllers\ArticleController@searchByRubricAndDate');

            $r->addRoute('GET', '/searchByDateAndKeyword', '\App\Http\Controllers\ArticleController@searchByDateAndKeywordPage');
            $r->addRoute('POST', '/searchByDateAndKeyword', '\App\Http\Controllers\ArticleController@searchByDateAndKeyword');

            $r->addRoute('GET', '/searchByRubricAndKeyword', '\App\Http\Controllers\ArticleController@searchByRubricAndKeywordPage');
            $r->addRoute('POST', '/searchByRubricAndKeyword', '\App\Http\Controllers\ArticleController@searchByRubricAndKeyword');

            $r->addRoute('GET', '/searchByRubricDateWithLatin', '\App\Http\Controllers\ArticleController@searchByRubricDateWithLatinPage');
            $r->addRoute('POST', '/searchByRubricDateWithLatin', '\App\Http\Controllers\ArticleController@searchByRubricDateWithLatin');

            $r->addRoute('GET', '/searchByRubricAndInitial', '\App\Http\Controllers\ArticleController@searchByRubricAndInitialPage');
            $r->addRoute('POST', '/searchByRubricAndInitial', '\App\Http\Controllers\ArticleController@searchByRubricAndInitial');

            $r->addRoute('GET', '/searchByRubricAndYear', '\App\Http\Controllers\ArticleController@searchByRubricDateContainingYearPage');
            $r->addRoute('POST', '/searchByRubricAndYear', '\App\Http\Controllers\ArticleController@searchByRubricDateContainingYear');
        });
    }
}