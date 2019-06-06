<?php
namespace Mkoveni\Lani\Filesystem\Filters;

class FileFilter extends \RecursiveFilterIterator
{

    public function accept()
    {
        var_dump($this->getInnerIterator()->current()->isFile());
        return $this->getInnerIterator()->current()->isFile();
    }

}