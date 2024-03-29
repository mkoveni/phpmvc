<?php

use Mkoveni\Lani\Collection\Collection;


if (!function_exists('container')) {

    /**
     * gets the container instance
     *
     * @return \Mkoveni\Lani\DI\ContainerInterface
     */
    function container()
    {
        return \Mkoveni\Lani\DI\Container::getInstance();
    }
}

if (!function_exists('collect')) {
    /**
     * return a new collection instance
     *
     * @param array $data
     * @return Collection
     */
    function collect($data): Collection
    {
        return new Collection($data);
    }
}


if (!function_exists('rootDir')) {

    function rootDir($path = '')
    {
        return container()->get('rootDir') . DIRECTORY_SEPARATOR . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('configDir')) {

    function configDir($path = '')
    {
        return container()->get('configDir') . DIRECTORY_SEPARATOR . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('routesDir')) {

    function routesDir($path = '')
    {
        return container()->get('routesDir') . DIRECTORY_SEPARATOR . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('config')) {
    function config($key, $default = null)
    {

        return Config::get($key, $default);
    }
}

if (!function_exists('json_print')) {

    function json_print($json, $html = false) {
		$tabcount = 0; 
		$result = ''; 
		$inquote = false; 
		$ignorenext = false; 
		if ($html) { 
		    $tab = "&nbsp;&nbsp;&nbsp;"; 
		    $newline = "<br/>"; 
		} else { 
		    $tab = "\t"; 
		    $newline = "\n"; 
		} 
		for($i = 0; $i < strlen($json); $i++) { 
		    $char = $json[$i]; 
		    if ($ignorenext) { 
		        $result .= $char; 
		        $ignorenext = false; 
		    } else { 
		        switch($char) { 
		            case '{': 
		                $tabcount++; 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '}': 
		                $tabcount--; 
		                $result = trim($result) . $newline . str_repeat($tab, $tabcount) . $char; 
		                break; 
		            case ',': 
		                $result .= $char . $newline . str_repeat($tab, $tabcount); 
		                break; 
		            case '"': 
		                $inquote = !$inquote; 
		                $result .= $char; 
		                break; 
		            case '\\': 
		                if ($inquote) $ignorenext = true; 
		                $result .= $char; 
		                break; 
		            default: 
		                $result .= $char; 
		        } 
		    } 
		} 
		return $result; 
	}
}
