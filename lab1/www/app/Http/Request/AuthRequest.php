<?php

namespace App\Http\Request;


use Core\Base\Http\Request;

class AuthRequest extends Request
{
    public $username;
    public $dbname;
}