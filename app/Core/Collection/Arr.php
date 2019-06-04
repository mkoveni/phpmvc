<?php

namespace App\Core\Collection;

/**
 * Array Helper classr_iterator
 * @author Rivalani Simon Hlengani <simon@iamrivalani.co.za>
 */
class Arr 
{
    public static function accessible($items)
    {
        return is_array($items) || $items instanceof \ArrayAccess;
    }

    public static function exists($arr, $value)
    {
        if($arr instanceof \ArrayAccess) {

            return $arr->offsetExists($value);
        }

        return array_key_exists($value, $arr);
    }

    public static function first($arr, callable $callable = null, $default = null)
    {
        if(is_null($callable)){

            if(empty($arr)) {

                return $default;
            }

            return $arr[0] ?? $default;
        }

        foreach($arr as $key => $value) {

            if(call_user_func($callable, $value, $key)) {

                return $value;
            }
        }

        return $default;
    }

    public static function flattern($arr)
    {
        $flat = [];

        if(static::accessible($arr)) {
    
            $iterator = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr));
    
            foreach($iterator as $it) {
    
                array_push($flat, $it);
            }
        }

       

        return $flat;
    }

    public static function last($arr, callable $callable = null, $default = null)
    {
        return static::first(array_reverse($arr), $callable, $default);
    }

    public static function get($arr, string $key, $default = null)
    {
        if(!static::accessible($arr))
        {
            return $default;
        }

        if(static::exists($arr, $key))
        {
            return $arr[$key];
        }


        foreach(explode('.', $key) as $seg)
        {
            if(static::accessible($arr) && static::exists($arr, $seg))
            {
                $arr = $arr[$seg];
            }
            else {

                return $default;
            }
        }

        return $arr;
    }

    public static function has($arr, $key)
    {
        foreach(explode('.', $key) as $seg)
        {
            if(static::accessible($arr) && static::exists($arr, $seg))
            {
                $arr = $arr[$seg];
            }
            else {

                return false;
            }
        }

        return true;
    }

    public static function groupBy(array $arr, string $by): array
    {
        $grouped = [];

        foreach($arr as $value) {

            if(static::exists($value, $by) && static::isAssoc($value)) {

                $grouped[$value[$by]][] = $value;
            }
        }
        return $grouped;
    }

    public static function isAssoc(array $arr)
    {
        return count(array_filter(array_keys($arr), 'is_string')) > 0;
    }

    public static function only($arr, $keys = [])
    {
        return array_intersect_key($arr, array_flip($keys));
    }

    public static function where($arr, callable $callable)
    {
        return array_filter($arr, $callable, ARRAY_FILTER_USE_BOTH);
    }
}