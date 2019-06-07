<?php

namespace Mkoveni\Lani\Router;

use Mkoveni\Lani\Collection\Collection;

class RouteCollection implements \IteratorAggregate
{
    protected $routes;


    public function __construct() {
        $this->routes = new Collection;
    }

    public function add(Route $route) {

        $this->routes->add($route);

        return $this;
    }

    /**
     * Gets a route by its uri
     *
     * @param [string] $uri
     * @return Route
     */
    public function getByUri(string $uri): ?Route {

        return $this->routes->first(function(Route $route) use($uri){

            return $route->getUri() === $uri;
        });
    }

    public function getIterator()
    {
        return $this->routes->getIterator();
    }
}