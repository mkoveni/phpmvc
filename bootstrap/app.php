<?php

use Mkoveni\Lani\App;
use Mkoveni\Lani\Routing\Router;

require __DIR__ .'/autoload.php';

$app = new App(__DIR__ . '/../');


$router = $app->getContainer()->get(Router::class);