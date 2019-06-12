<?php

namespace Mkoveni\Lani\Routing;

use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Routing\Middleware\RequestHandlerInterface;
use Mkoveni\Lani\Routing\Middleware\MiddlewareInterface;

class Dispatcher implements RequestHandlerInterface
{
    /**
     * Middleware stack
     *
     * @var \Mkoveni\Lani\Routing\Middleware\MiddlewareInterface[]
     * */
    protected $middleware = [];

    public function __construct(array $middleware)
    {
        $this->middleware = $middleware;
    }

    public function addMiddleware(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }

    public function handle(RequestInterface $request): RequestInterface
    {

       

        $middleware = current($this->middleware);

        var_dump($middleware);

        if(!$middleware ) {

            throw new \RuntimeException('Invalid server response, please make sure callable returns a response');
        }

        next($this->middleware);

        return $middleware->process($request, $this);
    }
}