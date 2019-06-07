<?php

use Mkoveni\Lani\App;
use Mkoveni\Lani\Filesystem\Finder;



require __DIR__ .'/autoload.php';

$app = new App;

$files = Finder::create();

$files->in(__DIR__ . '/../app');


require_once __DIR__ . '/../routes/web.php';