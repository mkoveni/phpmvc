<?php

use Mkoveni\Lani\App;

require __DIR__ .'/autoload.php';

$app = new App(__DIR__ . '/../');

require_once __DIR__ . '/../routes/web.php';