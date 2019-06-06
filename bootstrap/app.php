<?php
use App\Core\App;


require __DIR__ .'/autoload.php';

$app = new App;

require_once __DIR__ . '/../routes/web.php';