<?php
use Mkoveni\Lani\Filesystem\Finder;
use Mkoveni\Lani\Filesystem\Filesystem;

require_once __DIR__ .'/bootstrap/autoload.php';

$files = Finder::create();

$files->in(__DIR__ . '/test');

$fs = new Filesystem;

$namespace = 'Ali';
$toReplace = 'Mkoveni\\Testing';

$search = [
    'namespace '. $namespace . ';',
    $namespace . '\\'
];

$replace = [
    'namespace ' . $toReplace . ';',
    $toReplace . '\\'
];

foreach($files as $file) {

    if($file->isDir()) continue;
    
    if($fs->exists($file)) {

        $fs->put($file->getRealpath(), str_replace($search, $replace, $fs->get($file->getRealpath())));
        
       echo  "Done..\n";
    }
}