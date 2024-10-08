<?php

namespace Core\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    protected $message = 'View file not found';
}