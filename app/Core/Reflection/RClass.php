<?php

namespace App\Core\Reflection;

class RClass extends AbstractReflector
{
    protected function getReflectorInstance($arg)
    {
        return new \ReflectionClass($arg);
    }

    protected function buildArguments()
    {
        $reflector = $this->reflector;

        if ($reflector instanceof \ReflectionClass) {

            $this->arguments = collect($reflector->getConstructor()->getParameters())
                ->map(function (\ReflectionParameter $param) {
                    $newParam = new Dependency($param->getName(), null);

                    if ($param->getClass()) {

                        $newParam->setClass($param->getClass()->getName());
                    }
                    
                    return $newParam;
                });
        }
    }

    public function invoke(array $paramaters)
    {
        return $this->reflector->invoke($paramaters);
    }
}
