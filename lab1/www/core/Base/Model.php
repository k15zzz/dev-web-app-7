<?php

namespace Core\Base;

use Core\Base\Utils\ArrayClass;

class Model extends ArrayClass
{
    protected array $required = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function toExecuteFormat($arguments = [])
    {
        $format = [];

        foreach ($arguments as $key) {
            $format[':' . $key] = $this->$key;
        }

        return $format;
    }

    public function isNull()
    {
        foreach ($this as $key => $value) {
            if (in_array($key, $this->required) && !isset($value)) {
                return true;
            }
        }

        return false;
    }
}