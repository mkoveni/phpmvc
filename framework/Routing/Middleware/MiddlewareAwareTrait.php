<?php

namespace Mkoveni\Lani\Routing\Middleware;

use Psr\Http\Server\MiddlewareInterface;

trait MiddlewareAwareTrait
{
    /**
     * Middleware Stack
     *
     * @var MiddlewareInterface[]
     */
    protected $middleware = [];

    public function getMiddlewareStack()
    {
        return $this->middleware;
    }

    public function addMiddleToStack(MiddlewareInterface $middleware)
    {
        array_push($this->middleware, $middleware);
    }
}