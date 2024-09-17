<?php

namespace Core;

use Core\Contracts\IContainerLoader;
use Dotenv\Dotenv;

/**
 * Точка создания App.
 * Инициализирует единожды класс App.
 */
class Bootstrap
{
    private static App $app;

    public static function create(IContainerLoader $containerLoader): App
    {
        static::loadEnv();
        static::singleton($containerLoader);
        return static::$app;
    }

    private static function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

    private static function build(IContainerLoader $containerLoader): App
    {
        return new App($containerLoader::call());
    }

    private static function singleton(IContainerLoader $containerLoader): void
    {
        if (!isset(static::$app)) {
            static::$app = static::build($containerLoader);
        }
    }
}