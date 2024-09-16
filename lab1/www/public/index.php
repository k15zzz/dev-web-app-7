<?php

require __DIR__ . '/../vendor/autoload.php';

use App\AppRouter;
use Core\Bootstrap;
use App\Config;
use Core\Router;

session_start();
$config = new Config();
$app = Bootstrap::create($config);

$appRouter = new AppRouter();
Router::call($appRouter, $app);
