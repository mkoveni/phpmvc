<?php

namespace App\Core\Config\Parsers;

interface ParserInterface
{
    function parse($file);
}