<?php

namespace App\Core;

use App\Core\DI\Container;
use App\Core\Router\Route;
use App\Core\Reflection\RFunction;
use App\Core\DI\Dependency;
use App\Core\Reflection\RFactory;
use App\Core\Reflection\AbstractReflector;
use App\Core\Exceptions\ClassNotFoundException;
use App\Core\Facades\Router;

class App 
{
    protected $container;

    protected $serviceProviders = [
        \App\Core\Providers\AppServiceProvider::class
    ];

    protected $facades = [
        Router::class
    ];

    protected $settings = [
        'app' => [
            'debug' => true,
            'name' => 'Appy'
        ]
    ];

    public function __construct()
    {
        $this->container = Container::getInstance();

        $this->container->set('settings', function(){

            return $this->settings;
        });

        Router::setContainer($this->container);

        $this->registerProviders();

    }
    public function getContainer()
    {
        return $this->container;
    }

    public function get($uri, $callable)
    {
        $this->container->get(Router::class)->addRoute($uri, $callable, ['GET', 'POST']);
    }

    protected function registerProviders()
    {
        foreach($this->serviceProviders as $provider)
        {
            $this->container->registerProvider($provider);
        }
    }

    public function run()
    {
        $router = $this->container->get(Router::class);

        $route = $router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD']);

        $this->process($route);
    }

    protected function process(Route $route)
    {
        $handler = $route->getHandler();

        

        if($handler instanceOf \Closure) {

            $reflector = $this->getReflector('function', $handler);

            $params = $reflector->getClassArguments();

            $resolved = $params->map([$this, 'resolveFromContainer'])->toArray();

            echo call_user_func_array($handler, array_merge($route->getData(), $resolved));


        }

        if(is_array($handler) && count($handler) === 2)
        {
            [$controller, $method] = $handler;

            
            if(!class_exists($controller)) {

                throw new ClassNotFoundException(sprintf('Controller %s Could not be found.', $controller));
            }

            $controller = $this->container->get($controller);

            if(!method_exists($controller, $method))
            {
                throw new \BadMethodCallException(sprintf('%s Has no method %s', $controller, $method));
            }

            $reflector = $this->getReflector('method', [$controller, $method]);

            $params = $reflector->getClassArguments();

            $resolved = $params->map([$this, 'resolveFromContainer'])->toArray();

            echo call_user_func_array([$controller, $method], array_merge($route->getData(), $resolved));



        }
    }

    public function resolveFromContainer(Dependency $dependency)
    {
        return $this->container->get($dependency->getClass());
    }

    /**
     * Undocumented function
     *
     * @param [type] $type
     * @param [type] $args
     * @return AbstractReflector
     */
    public function getReflector($type, $args)
    {
        return $this->container->get(RFactory::class)->make($type, $args);
    }
}