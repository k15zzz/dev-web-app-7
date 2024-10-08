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
        $container = new Container();

        // Получаем данные из .env файла
        $host = $_ENV['DB_HOST'];
        $db = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $port = $_ENV['DB_PORT'];

        // Формируем DSN строку
        $dsn = "pgsql:host=$host;dbname=$db;port=$port";

        // Опции для PDO
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Режим обработки ошибок
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // По умолчанию возвращаем ассоциативные массивы
            PDO::ATTR_EMULATE_PREPARES => false,                 // Отключаем эмуляцию подготовленных запросов
        ];

        try {
            $container->set(\PDO::class, new PDO($dsn, $user, $pass, $options));
        } catch (PDOException $e) {
            echo 'Ошибка подключения к базе данных: ' . $e->getMessage();
            die();
        }

        return $container;
    }
}
