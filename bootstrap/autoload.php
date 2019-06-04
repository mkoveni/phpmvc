<?php

$dir_separator = DIRECTORY_SEPARATOR;

$root = __DIR__ . "{$dir_separator}..{$dir_separator}";

$autoloadFile = $root . 'autoload.json';

if(file_exists($autoloadFile))
{
    $namespaces = json_decode(file_get_contents($autoloadFile), true)['psr4'] ?? [];

    foreach($namespaces as $namespace => $dirs)
    {
        if(!is_array($dirs)) {
            $dirs = (array) $dirs;
        }

        spl_autoload_register(function($class) use($namespace, $dirs, $root){

            if(preg_match('/^'. preg_quote($namespace) .'/', $class)) {

                $class = str_replace($namespace, '', $class);
                $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

                foreach($dirs as $dir) {

                    $file = $root . $dir . DIRECTORY_SEPARATOR .  $class . '.php';
                  
                    if(file_exists($file))
                    {
                        require_once $file;

                        return;
                    }

                }

                return false;
            }
        });
    }
}