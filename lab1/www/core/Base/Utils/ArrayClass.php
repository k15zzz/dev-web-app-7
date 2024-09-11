<?php

namespace Core\Base\Utils;

abstract class ArrayClass
{
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}