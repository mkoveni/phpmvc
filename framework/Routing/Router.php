<?php

namespace Mkoveni\Lani\Routing;

use Mkoveni\Lani\Exceptions\MethodNotSupportedException;
use Mkoveni\Lani\Exceptions\RouteNotFoundException;
use Psr\Http\Message\ServerRequestInterface;

class Router
{


    const PARAMETER_REGEX = "\{[a-zA-Z][a-zA-Z0-9\_\-]*(:[^{}]+)?\}";
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

    public function dispatch(ServerRequestInterface $request)
    {
        $uri = $request->getUri()->getPath();

        $method = $request->getMethod();

        if (strpos($uri, '?') !== false) {

            $uri = substr($uri, 0, (strpos($uri, '?')));
        }

        $route = $route = $this->routes->getByUri($uri) ?? $this->resolveByRegex($uri);


        if ($route) {

            if (!$route->allows($method))
                throw new MethodNotSupportedException('That request method is not supported.');


            return $route;
        }

        throw new RouteNotFoundException(sprintf('Route %s could not be found.', $uri));
    }

    public function urlFor($name, array $parameters)
    {
        $route = $this->routes->getByName($name);

        if (!$route) { }

        $uri = $route->getUri();

        if (preg_match_all('#\{([a-z]+)(?::.+?)?\}#', $uri, $matches, PREG_SET_ORDER)) {

            foreach ($matches as $match) {

                [$variable, $paramName] = $match;

                if (isset($parameters[$paramName])) {
                    $uri = str_replace($variable, $parameters[$paramName], $uri);
                }
            }
        }

        return $uri;
    }

    protected function resolveByRegex($uri)
    {
        $routes = $this->routes;

        foreach ($routes as $route) {

            if ($pattern = $this->swapPlaceHolderWithPattern($route->getUri())) {
                if (preg_match("#^$pattern$#", $uri, $matches)) {

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

        return preg_replace_callback('#' . $pattern . '#', [$this, 'replace'], $uri);
    }

    protected function replace($matches)
    {
        if (count($matches) === 1) {

            return '([\w]+)';
        }

        return '(' . substr($matches[1], 1) . ')';
    }


    private function addRoute($uri, $callable, $methods = ['GET'])
    {
        $route =  new Route($uri, $callable, $methods);

        $this->routes->add($route);

        return $route;
    }

    public function get($uri, $callable)
    {
        return $this->addRoute($uri, $callable, ['GET', 'HEAD']);
    }

    public function post($uri, $callable)
    {
        return $this->addRoute($uri, $callable, ['POST']);
    }

    public function patch($uri, $callable)
    {
        return $this->addRoute($uri, $callable, ['PATCH']);
    }

    public function put($uri, $callable)
    {
        return $this->addRoute($uri, $callable, ['PUT']);
    }

    public function delete($uri, $callable)
    {
        return $this->addRoute($uri, $callable, ['DELETE']);
    }

    public function map($uri, $callable, $methods = ['GET', 'HEAD'])
    {
        return $this->addRoute($uri, $callable, $methods);
    }
}
