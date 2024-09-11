<?php

namespace Core\Base;

class Container
{
    public array $container = [];

    public function in(string $class): bool
    {
        return array_key_exists($class, $this->container);
    }

    public function get($key)
    {
        return $this->container[$key];
    }

    public function set($key, $instance): void
    {
        $this->container[$key] = $instance;
    }
}