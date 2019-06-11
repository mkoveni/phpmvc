<?php

namespace Mkoveni\Lani\Routing;

use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Http\ResponseInterface;

interface MiddlewareInterface
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, callable $callable);
}