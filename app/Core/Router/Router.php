<?php

namespace App\Core\Router;

use App\Core\Exceptions\MethodNotSupportedException;

class Router 
{
    const PARAMETER_REGEX="\{[a-zA-Z][a-zA-Z0-9\_\-]*(:[^{}]+)?\}";
    /**
     * 
     *
     * @var RouteCollection
     */
    protected $routes;

    protected $path;

    public function __construct()
    {
        $this->routes = new RouteCollection;
    }

    public function addRoute($uri, callable $callable, $methods = ['GET'])
    {
        $this->routes->add(new Route($uri, $callable, $methods));
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setPath($path = '/')
    {
        $this->path = $path;
    }

    public function getResponse()
    {
        return  $this->routes[$this->path] ?? null;
    }

    public function dispatch(string $uri, string $method)
    {
        if($route = $this->routes->getByUri($uri))
        {
            
            if(!$route->allows($method)) {

                throw new MethodNotSupportedException('That request method is not supported.');
            }
        }

        if($route = $this->resolveByRegex($uri)) {

            
        }

        return $route;
        
    }

    protected function resolveByRegex($uri)
    {
        $routes = $this->routes;

        foreach($routes as $route) {

            if($pattern = $this->swapPlaceHolderWithPattern($route->getUri()))
            {
                if(preg_match("#^$pattern$#", $uri, $matches)) {

                    unset($matches[0]);
                    
                    $route->setData($matches);

                    return $route;
                }
            }
        }

        return null;
    }

    protected function swapPlaceHolderWithPattern($uri)
    {
        $pattern = self::PARAMETER_REGEX;

        return preg_replace_callback('#'. $pattern. '#', [$this, 'replace'], $uri);
    }

    protected function replace($matches)
    {
        if(count($matches) === 1) {

            return '([\w]+)';
        }

        return '('. substr($matches[1], 1) .')';
    }
}