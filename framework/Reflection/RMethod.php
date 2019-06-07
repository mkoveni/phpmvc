<?php

namespace Mkoveni\Lani\Reflection;

class RMethod extends AbstractReflector 
{
    protected function getReflectorInstance($arg)
    {
        if(!is_array($arg)) {

            throw new \ReflectionException(sprintf('Invalid reflection parameter. %s Reflector Requires an array contain class 
                and method as a parameter', get_class($this)));
        }

        return new \ReflectionMethod($arg[0], $arg[1]);

    }

    public function invoke(array $paramaters)
    {
        return $this->reflector->invoke($paramaters);
    }
}