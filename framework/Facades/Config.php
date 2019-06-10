<?php

namespace Mkoveni\Lani\Facades;

class Config extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Mkoveni\Lani\Config\Config::class;
    }
}