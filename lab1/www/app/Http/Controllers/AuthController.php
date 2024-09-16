<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\NotAuthMiddleware;
use App\Http\Request\AuthRequest;
use App\Repositories\UserRepository;
use Core\Base\Layers\Controller;
use Core\Exceptions\ViewNotFoundException;
use Core\View;

class AuthController extends Controller
{
    /**
     * @throws ViewNotFoundException
     */
    public function auth(AuthRequest $request, UserRepository $repository, NotAuthMiddleware $middleware): string
    {
        if ($repository->check($request->username, $request->dbname)) {
            $repository->auth($request->username);
            return View::render('auth', ['status' => true, 'visible' => true], 'default');
        }
        return View::render('auth', ['status' => false, 'visible' => true], 'default');
    }

    /**
     * @throws ViewNotFoundException
     */
    public function authPage(NotAuthMiddleware $middleware): string
    {
        return View::render('auth', ['visible' => false], 'default');
    }

    /**
     * @throws ViewNotFoundException
     */
    public function logout(AuthMiddleware $middleware, UserRepository $repository): string
    {
        $repository->logout();
        header("Location: /auth");
        die();
    }
}