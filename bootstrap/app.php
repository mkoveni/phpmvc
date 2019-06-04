<?php
use App\Core\App;
use App\Core\Router\Router;
use App\Core\Router\RouteCollection;
use App\Core\Router\Route;

require __DIR__ .'/autoload.php';

$app = new App;

$uri = '/user/2/posts/2019';

$c = $app->getContainer();

$c->share(Router::class, function(){

    return new Router;
});

$routes = new RouteCollection;

// $routes->add(new Route('/users/all', function(){
//     echo 'This Life is worth living.';
// }));

$app->get('/user/{id}/posts/{year:[0-9]+}', function(int $id, int $year){
    var_dump($id, $year);
    return 'testing';
});

$app->get('/users', function(){
    return 'users';
});





