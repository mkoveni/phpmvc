<?php

use Mkoveni\Lani\App;
use Mkoveni\Lani\View\Template;
use Mkoveni\Lani\Filesystem\Filesystem;

require __DIR__ .'/autoload.php';

$app = new App;

$template = new Template(__DIR__ . '/../template/', new Filesystem);

$template->render('home.html', []);

require_once __DIR__ . '/../routes/web.php';