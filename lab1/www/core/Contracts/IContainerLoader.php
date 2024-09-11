<?php

namespace Core\Contracts;

use Core\Base\Container;

interface IContainerLoader
{
    public static function call(): Container;
}
