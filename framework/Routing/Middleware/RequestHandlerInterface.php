<?php

namespace Mkoveni\Lani\Routing\Middleware;

use Mkoveni\Lani\Http\RequestInterface;

interface RequestHandlerInterface
{
    public function handle(RequestInterface $request): RequestInterface;
}