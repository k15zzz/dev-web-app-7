<?php

namespace App\Http\Request;

use Core\Base\Http\Request;

class ProducerRequest extends Request
{
    public $producer;
    public $date;
}
