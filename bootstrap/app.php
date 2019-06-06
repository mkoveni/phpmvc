<?php

use Mkoveni\Lani\Filesystem\Finder;
use App\Core\App;


require __DIR__ .'/autoload.php';

$app = new App;

$files = Finder::create();

$files->in(__DIR__ . '/../app');


require_once __DIR__ . '/../routes/web.php';