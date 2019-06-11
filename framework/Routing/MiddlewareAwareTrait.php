<?php

namespace Mkoveni\Lani\Routing;

use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Http\ResponseInterface;

trait MiddlewareAwareTrait
{
    protected $middleware = [];

    protected $processing = false;

    protected $previosCallable;

    public function addMiddleware($middleware)
    {
        $this->middleware[] = $middleware;

        return $this;
    }

    public function setLastMiddleware(callable $callable= null)
    {
        if(is_null($this->previosCallable)) {

            if($callable = null) {

                $callable = $this;
            }

            $this->previosCallable = $callable;

            return;
        }

        throw new \RuntimeException('The middleware has already been push to the stack');
    }

    public function callMiddlewareStack(RequestInterface $request, ResponseInterface $response)
    {
        if(!$this->previosCallableMiddleware) {

            $this->setLastMiddleware();
        }

        $this->processing = true;

        $response = $this->previosCallable($request, $response);

        $this->processing = false;

        return $response;
    }
}