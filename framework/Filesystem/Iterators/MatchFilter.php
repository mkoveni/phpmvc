<?php

namespace Mkoveni\Lani\Filesystem\Iterators;

class MatchFilter extends \FilterIterator
{
    protected $pattern;

    public function __construct(\Iterator $iterator, $pattern)
    {
        parent::__construct($iterator);
        
        $this->pattern = $pattern;    
    }

    public function accept()
    {
        return preg_match("#$this->pattern#", $this->current()->getPathname());
    }
}