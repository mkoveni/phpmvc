<?php

namespace App\Core\DI;

use App\Core\Exceptions\DependencyNotFoundException;
use App\Core\Providers\AbtractServiceProvider;
use App\Core\Exceptions\InvalidProviderException;

class Container implements ContainerInferface
{
    private static $instance;

    protected $items = [];

    public function get($key)
    {
        if($this->has($key)) {

            return $this->items[$key]($this);
        }

        return $this->autowire($key);
    }

    public function has($key)
    {
        return isset($this->items[$key]);
    }

    public function set($key, callable $closure)
    {
        $this->items[$key] = $closure;
    }

    public function share($key, callable $closure)
    {
      $this->items[$key] = function() use ($closure) {

        static $resolved;

        if(!$resolved) {

            $resolved = $closure();
        }

        return $resolved;
      };

    }

    public function __get($name)
    {
        return $this->get($name);
    }

    private function autowire($name)
    {
        if(!class_exists($name)) {

            throw new DependencyNotFoundException;
        }

        $reflector = $this->getReflactor($name);

        if(!$reflector->isInstantiable()) {

            throw new DependencyNotFoundException;
        }

        if($constructor = $reflector->getConstructor())
        {
            return $reflector->newInstanceArgs(
                $this->getReflectorConstructorDependencies($constructor)
            );
        }

        return new $name;
    }

    private function getReflactor($class)
    {
        return new \ReflectionClass($class);
    }

    private function getReflectorConstructorDependencies($constructor)
    {
        return array_map(function(\ReflectionParameter $dep) {

            return $this->resolveDependecy($dep);
           
        }, $constructor->getParameters());
    }

    private function resolveDependecy(\ReflectionParameter $dependency)
    {
        if(is_null($dependency->getClass())) {

            throw new DependencyNotFoundException;
        }

        return $this->get($dependency->getClass()->getName());
    }

    public function registerProvider($provider)
    {
        if(is_string($provider)) {

            if(class_exists($provider) && is_subclass_of($provider, AbtractServiceProvider::class)) {

                (new $provider)->register();

                return;
            }
        }
        else if(is_object($provider) && $provider instanceOf AbtractServiceProvider ) {
            $provider->register();

            return;
        }

        throw new InvalidProviderException(is_object($provider) ? $provider : new $provider);
    }


    public static function getInstance()
    {
        if(!static::$instance) {
            
            static::$instance = new static;
        }

        return static::$instance;
    }
}