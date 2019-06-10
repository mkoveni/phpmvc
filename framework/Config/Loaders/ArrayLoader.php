<?php

namespace Mkoveni\Lani\Config\Loaders;

class ArrayLoader implements Loader
{
    protected  $files = [];

    public function __construct(array $files)
    {
        $this->files = $files;   
    }

    public function parse()
    {
        $config = [];

        foreach($this->files as $key => $file) {

            $config[array_keys($file)[0]] = require_once  array_values($file)[0];

        }

        return $config;
    }
}