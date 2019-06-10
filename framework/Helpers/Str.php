<?php

namespace Mkoveni\Lani\Helpers;

class Str
{
    public static function snakeCase(string $string):?string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }
}