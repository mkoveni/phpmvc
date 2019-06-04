<?php

namespace App\Core;

use App\Core\DI\Container;
use App\Core\Router\Router;

class App 
{
    protected $container;


    protected $settings = [
        'app' => [
            'debug' => true,
            'name' => 'Appy'
        ]
    ];

    public function __construct()
    {
        $this->container = new Container;

        $this->container->set('settings', function(){

            return $this->settings;
        });

        $this->container->share(Router::class, function(){
            return new Router;
        });
    }
    public function getContainer()
    {
        return $this->container;
    }

    public function get($uri, callable $callable)
    {
        $this->container->get(Router::class)->addRoute($uri, $callable, ['GET']);
    }

    public function run()
    {
        $router = $this->container->get(Router::class);

        $route = $router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD']);

        

        echo call_user_func_array($route->getHandler(), $route->getData());
    }
}