<?php

namespace Mkoveni\Lani\Config\Parsers;


class ArrayParser implements ParserInterface
{
    public function parse($file): array
    {        
        return require $file;
    }
}