<?php

namespace Core;

use Core\Contracts\IRouterDispatcher;
use FastRoute\Dispatcher;

class Router
{
    public static function call(IRouterDispatcher $routerDispatcher, App $app)
    {
        $dispatcher = $routerDispatcher::call();

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $dispatch = $dispatcher->dispatch($httpMethod, $uri);
        switch ($dispatch[0]) {
            case Dispatcher::NOT_FOUND:
                try {
                    echo View::render('404');
                } catch (Exceptions\ViewNotFoundException) {
                    http_response_code(404);
                    echo '404 Not Found';
                }
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                try {
                    echo View::render('405');
                } catch (Exceptions\ViewNotFoundException) {
                    http_response_code(405);
                    echo '405 Method Not Allowed';
                }
                break;
            case Dispatcher::FOUND:
                [$_, $handler, $vars] = $dispatch;
                if (is_callable($handler)) {
                    echo call_user_func_array($handler, $vars);
                } elseif (is_string($handler)) {
                    [$controllerName, $method] = explode('@', $handler);
                    echo $app->make($controllerName, $method);
                }
                break;
        }
    }
}
