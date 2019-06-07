<?php

namespace Mkoveni\Lani\Facades;

use Mkoveni\Lani\Routing\Router as Route;

class Router extends Facade
{
    public static function getFacadeAccessor()
    {
        return Route::class;
    }
}