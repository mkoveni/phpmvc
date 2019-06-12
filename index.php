<?php
use Mkoveni\Lani\Routing\Middleware\MiddlewareInterface;
use Mkoveni\Lani\Http\RequestInterface;
use Mkoveni\Lani\Routing\Middleware\RequestHandlerInterface;
use Mkoveni\Lani\Http\ResponseInterface;
use Mkoveni\Lani\Http\Request;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/bootstrap/app.php';


class Authenticate implements MiddlewareInterface
{
    public function process(RequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
    {
        return $handler->handle($request);
    }
}

$app->run(new Request);


