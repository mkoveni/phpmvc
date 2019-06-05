<?php

namespace App\Core\Reflection;

use App\Core\DI\Dependency;
use App\Core\Collection\Collection;

abstract class AbstractReflector 
{
    protected $reflector;

    /**
     * 
     *
     * @var \App\Core\Collection\Collection
     */
    protected $arguments;


    public function __construct($arg)
    {
        $this->reflector = $this->getReflectorInstance($arg);

        $this->buildArguments();
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     *
     * @return Collection
     */
    public function getClassArguments()
    {
        return $this->arguments->where(function(Dependency $dep){

            return !is_null($dep->getClass());
        });
    }


    protected function buildArguments()
    {
        $this->arguments =  collect($this->reflector->getParameters())
            ->map(function (\ReflectionParameter $param) {


                $newParam = new Dependency($param->getName(), null);

                if ($param->getClass()) {

                    $newParam->setClass($param->getClass()->getName());
                }

                return $newParam;
            });
    }

    protected abstract function getReflectorInstance($arg);
}
