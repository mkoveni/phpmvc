<?php

namespace App\Core\Facades;

abstract class Facade 
{
    protected static $container;

    public static function __callStatic($name, $arguments)
    {
        var_dump(static::getFacadeInstance());
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