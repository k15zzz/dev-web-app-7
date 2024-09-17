<?php

namespace App;

use Core\Base\Container;
use Core\Contracts\IContainerLoader;
use PDO;
use PDOException;

class Config implements IContainerLoader
{
    /**
     * Создает Container для App
     *
     * @return Container
     * @throws \Exception
     */
    public static function call(): Container
    {
        return new Container();
    }
}
