<?php

namespace Core\Base\Http;

use Core\Base\Utils\ArrayClass;

class Request extends ArrayClass
{
    private array $request;

    /**
     * Собираем все параметры в единый массив для
     * дальнейшей работы с ним в виде Request
     */
    public function __construct()
    {
        $request = array_merge($_REQUEST, []);
        $body = json_decode(file_get_contents('php://input'), true);
        if (is_array($body)) $request = array_merge($request, $body);
        foreach ($request as $key => $value) $this->$key = $value;
        $this->request = $request;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->get($name, $value);
    }

    public function get($key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }

    public function has($key)
    {
        return isset($this->request[$key]);
    }

    public function all()
    {
        return $this->request;
    }

    public function set($key, $value)
    {
        $this->request[$key] = $value;
    }

    public function remove($key)
    {
        unset($this->request[$key]);
    }
}