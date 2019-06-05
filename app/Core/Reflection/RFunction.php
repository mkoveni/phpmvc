<?php

namespace App\Core\Reflection;

use App\Core\Collection\Collection;

class RFunction extends AbstractReflector
{
    
    public function invoke(array $paramaters)
    {
        return $this->reflector->invoke($paramaters);
    }

    protected function getReflectorInstance($arg)
    {
        return new \ReflectionFunction($arg);
    }
}
