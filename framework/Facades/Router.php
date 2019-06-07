<?php

namespace Mkoveni\Lani\Facades;

use Mkoveni\Lani\Router\Router as Route;

class Router extends Facade
{
    public static function getFacadeAccessor()
    {
        return Route::class;
    }
}