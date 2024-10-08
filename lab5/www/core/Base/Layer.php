<?php

namespace Core\Base;

class Layer
{
    protected static array $useImplements = [];

    public static function getImplement($interface)
    {
        foreach (static::$useImplements as $implementClass) {
            if (static::isImplement($implementClass, $interface))
                return $implementClass;
        }
        return false;
    }

    public static function getVariadicImplements($interface)
    {
        $implements = [];

        foreach (static::$useImplements as $implementClass) {
            if (static::isImplement($implementClass, $interface))
                $implements[] = $implementClass;
        }

        return $implements;
    }

    private static function isImplement($implementClass, $interface)
    {
        $interfaces = class_implements($implementClass);
        return $interfaces && in_array($interface, $interfaces);
    }
}