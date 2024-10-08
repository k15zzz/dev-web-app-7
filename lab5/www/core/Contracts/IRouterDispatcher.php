<?php

namespace Core\Contracts;

use FastRoute\Dispatcher;

interface IRouterDispatcher
{
    public static function call(): Dispatcher;
}
