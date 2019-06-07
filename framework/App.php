<?php

namespace Mkoveni\Lani;

use Mkoveni\Lani\Exceptions\ClassNotFoundException;
use Mkoveni\Lani\DI\{Container, Dependency};
use Mkoveni\Lani\Routing\{Route, Router};
use Mkoveni\Lani\Reflection \ {
    RFactory, AbstractReflector, RClass};

class App
{
    protected $container;

    protected $serviceProviders = [
        \Mkoveni\Lani\Providers\AppServiceProvider::class
    ];

    protected $aliases = [
        \Mkoveni\Lani\Facades\Router::class
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

        $this->container->set('settings', function () {

            return $this->settings;
        });

        $this->registerProviders();

        $this->registerAliases();
    }
    public function getContainer()
    {
        return $this->container;
    }

    protected function registerProviders()
    {
        foreach ($this->serviceProviders as $provider) {
            $this->container->registerProvider($provider);
        }
    }

    public function run()
    {
        try {

            $router = $this->container->get(Router::class);

            $route = $router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD']);

            $this->process($route);

        } catch (\Exception $ex) {

            echo 'A server error has occured.';
         }
    }

    protected function process(Route $route)
    {
        $handler = $route->getHandler();



        if ($handler instanceof \Closure) {

            $reflector = $this->getReflector('function', $handler);

            $params = $reflector->getClassArguments();

            $resolved = $params->map([$this, 'resolveFromContainer'])->toArray();

            echo call_user_func_array($handler, array_merge($route->getData(), $resolved));
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;


            if (!class_exists($controller)) {

                throw new ClassNotFoundException(sprintf('Controller %s Could not be found.', $controller));
            }

            $controller = $this->container->get($controller);

            if (!method_exists($controller, $method)) {
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

    public function registerAliases()
    {
        foreach ($this->aliases as $alias) {
            $reflector = $this->getReflector('class', $alias);

            if ($reflector instanceof RClass) {

                $alias::setContainer($this->container);

                class_alias($alias, $reflector->getShortName());
            }
        }
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
