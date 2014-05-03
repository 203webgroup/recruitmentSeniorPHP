<?php

namespace DependencyInjection;

class Container
{
    protected static $real = null;

    public static function setRealContainer($container)
    {
        static::$real = $container;
    }

    public static function set($paramName, $value)
    {
        static::$real[$paramName] = $value;
    }

    public static function get($paramName)
    {
        return static::$real[$paramName];
    }
}
