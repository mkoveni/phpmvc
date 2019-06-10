<?php

namespace Mkoveni\Lani\Filesystem;

use Mkoveni\Lani\Exceptions\FileNotFoundException;


class Filesystem
{
    public function exists($file)
    {
        return file_exists($file);
    }

    public function get($file) {

        if($this->exists($file)) {

            return file_get_contents($file);
        }

        throw new FileNotFoundException(sprintf('%s Could not be found.', $file));
    }

    public function put($file, $content)
    {
        if(!$this->exists($file)) {
            throw new FileNotFoundException(sprintf('%s Could not be found.', $file));
        }

       file_put_contents($file, $content);
    }

    public static function getDirectoryScanner()
    {
        return new DirectoryScanner;
    }


}
