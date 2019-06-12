<?php
namespace Mkoveni\Lani\Routing\Middleware;

use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Http\ResponseInterface;

interface MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface;
}