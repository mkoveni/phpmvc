<?php

$dir_separator  = DIRECTORY_SEPARATOR;

$rootDir = __DIR__ . "{$dir_separator}..{$dir_separator}";

$packages_dir = "{$rootDir}packages/";

/**
 * AUTOLOAD THIRD PARTY PACKAGES
 */
autoload($packages_dir, "{$packages_dir}/autoload.json", $dir_separator);

/**
 * AUTOLOAD APP CLASSES
 */
autoload($rootDir, "{$rootDir}autoload.json", $dir_separator);

function autoload($root, $autoloadFile)
{
    if (file_exists($autoloadFile)) {
        $autoload =  json_decode(file_get_contents($autoloadFile), true);

        $namespaces = $autoload['psr4'] ?? [];

        $files = $autoload['files'] ?? [];



        foreach ($namespaces as $namespace => $dirs) {

            if (!is_array($dirs)) {
                $dirs = (array)$dirs;
            }

            spl_autoload_register(function ($class) use ($namespace, $dirs, $root) {


                if (preg_match('/^' . preg_quote($namespace) . '/', $class)) {

                    $class = str_replace($namespace, '', $class);
                    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

                    foreach ($dirs as $dir) {

                       
                        $file = $root . $dir . DIRECTORY_SEPARATOR .  $class . '.php';

                        if (file_exists($file)) {
                            require_once $file;

                            return;
                        }
                    }

                    return false;
                }
            });
        }


        foreach ($files as $file) {

            if (file_exists($root . $file)) {

                require_once $root . $file;
            }
        }
    }
}
