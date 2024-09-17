<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AuthMiddleware;
use Core\Base\Layers\Controller;
use Core\Exceptions\ViewNotFoundException;
use Core\View;

class IndexController extends Controller
{
    /**
     * @throws ViewNotFoundException
     */
    public function renderPage(): string
    {
        return View::render('index', [], 'default');
    }
}