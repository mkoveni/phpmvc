<?php

namespace Mkoveni\Lani\Filesystem\Iterators;

class FileFilter extends \FilterIterator
{
    const DIRECTORY = 1;
    const FILE = 2;

    protected $searchMode;

    public function __construct(\Iterator $iterator, int $searchMode)
    {
        parent::__construct($iterator);
        
        $this->searchMode = $searchMode;
    }

    public function accept()
    {
        if(static::DIRECTORY === $this->searchMode && $this->current()->isFile()) {

            return false;
        }

        if(static::FILE === $this->searchMode && $this->current()->isDir()) {

            return false;
        }
        return true;
    }
}