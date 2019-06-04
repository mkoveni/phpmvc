<?php

namespace App\Core\Config\Parsers;

class YamlParser implements ParserInterface 
{
    public function parse($file)
    {
        return \yaml_parse_file(file_get_contents($file));
    }
}