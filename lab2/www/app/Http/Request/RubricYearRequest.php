<?php

namespace App\Http\Request;

use Core\Base\Http\Request;

class RubricYearRequest extends Request
{
    public $rubric;
    public $date;
    public $year;
}
