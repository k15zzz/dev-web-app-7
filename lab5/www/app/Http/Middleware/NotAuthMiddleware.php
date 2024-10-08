<?php

namespace App\Http\Middleware;

use App\Repositories\Exceptions\UserNotAuthException;
use App\Repositories\UserRepository;
use Core\Base\Layers\Middleware;

class NotAuthMiddleware extends Middleware
{
    public function __construct(UserRepository $repository)
    {
        try {
            $repository->getAuth();
            header("Location: /");
            die();
        } catch (UserNotAuthException) {
        }
    }
}
