<?php

namespace App\Core\Facades;

abstract class Facade 
{
    protected static $container;

    public static function __callStatic($name, $arguments)
    {
        $instance = static::getFacadeInstance();

        return $instance->$name(...$arguments);
    }

    public static function getFacadeInstance()
    {
        $accessor = static::getFacadeAccessor();

        return static::$container->get($accessor);
    }

    public static function setContainer($container)
    {
        static::$container = $container;
    }
}