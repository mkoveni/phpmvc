<?php

namespace App\Core\Facades;

use App\Core\Router\Router as Route;

class Router extends Facade
{
    public function getFacadeAccessor()
    {
        return Route::class;
    }
}