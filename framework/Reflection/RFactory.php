<?php

namespace Mkoveni\Lani\Reflection;

class RFactory 
{
    protected $factories = [
        'class' => RClass::class,
        'method' =>RMethod::class,
        'function' =>RFunction::class
    ];


    /**
     * Undocumented function
     *
     * @param [type] $name
     * @param [type] $args
     * @return AbstractReflector
     */
    public function make($name, $args)
    {
        if(isset($this->factories[$name]) && class_exists($this->factories[$name]))
        {
            return new $this->factories[$name]($args);
        }
    }
}