<?php

namespace App\Http\Request;


use Core\Base\Http\Request;

class TestRequest extends Request
{
    public $id;
    public $fk_school_shift;
    public $fk_actirovka_type;
    public $fk_user;
    public $temperature;
    public $wind;
    public $updated_at;
    public $sent_at;
    public $created_at;
}