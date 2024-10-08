<?php

namespace App\Http\Middleware;

use App\Repositories\Exceptions\UserNotAuthException;
use App\Repositories\UserRepository;
use Core\Base\Layers\Middleware;

class AuthMiddleware extends Middleware
{
    public function __construct(UserRepository $repository)
    {
        try {
            $repository->getAuth();
        } catch (UserNotAuthException) {
            header("Location: /auth");
            die();
        }
    }
}
