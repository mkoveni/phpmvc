<?php
use App\Core\App;


use App\Core\Http\RequestInterface;
use App\Core\Facades\Router;

require __DIR__ .'/autoload.php';

$app = new App;

$app->get('/users', function(RequestInterface $requestInterface){

    return $requestInterface->httpUserAgent;
});

Router::get('users');

$app->get('/home', [\App\Http\Controllers\HomeController::class, 'index']);
$app->get('/welcome', [\App\Http\Controllers\HomeController::class, 'welcome']);





