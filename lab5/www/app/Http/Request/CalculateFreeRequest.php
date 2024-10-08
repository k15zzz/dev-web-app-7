<?php

namespace App\Http\Request;

use Core\Base\Http\Request;

class CalculateFreeRequest extends Request
{
    public $material;
    public $publication_type;
    public $pages;
}