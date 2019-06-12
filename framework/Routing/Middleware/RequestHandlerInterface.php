<?php

namespace Mkoveni\Lani\Routing\Middleware;

use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Http\ResponseInterface;

interface RequestHandlerInterface
{
    public function handle(RequestInterface $request): ResponseInterface;
}