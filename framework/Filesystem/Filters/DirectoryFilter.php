<?php

namespace Mkoveni\Lani\Filesystem\Filters;

class DirectoryFilter extends \RecursiveFilterIterator
{
    public function accept()
    {
        return $this->hasChildren();
    }
}