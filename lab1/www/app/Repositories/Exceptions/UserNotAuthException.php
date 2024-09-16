<?php

namespace App\Repositories\Exceptions;

use Exception;

class UserNotAuthException extends Exception
{
    protected $message = 'Пользователь не авторизован';
}