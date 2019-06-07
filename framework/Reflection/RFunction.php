<?php

namespace Mkoveni\Lani\Reflection;

use Mkoveni\Lani\Collection\Collection;

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
