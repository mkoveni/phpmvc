<?php

namespace Mkoveni\Lani\Filesystem;

use Mkoveni\Lani\Exceptions\FileNotFoundException;
use Mkoveni\Lani\Filesystem\Iterators\FileFilter;
use Mkoveni\Lani\Filesystem\Iterators\MatchFilter;

class DirectoryScanner implements \IteratorAggregate
{
    protected $dirs = [];

    protected $searchMode;

    protected $matches;


    public function searchDir($dirs)
    {
        $dirs = (array) $dirs;

        foreach($dirs as $dir) {
            if(is_dir($dir)) {
                $this->dirs[] = $dir; 
                continue;
            }

            throw new FileNotFoundException(sprintf('Could not directory %s', $dir));
        }

        return $this;
    }

    public function files()
    {
        $this->searchMode = FileFilter::FILE;

        return $this;
    }

    public function dirs()
    {
        $this->searchMode = FileFilter::DIRECTORY;

        return $this;
    }

    public function matches(string $pattern)
    {
        $this->matches = $pattern;

        return $this;
    }

    protected function processDirFilters(string $dir)
    {

        $flags = \RecursiveDirectoryIterator::SKIP_DOTS;

        $iterator = new \RecursiveDirectoryIterator($dir, $flags);

        if($this->searchMode) {
            $iterator = new FileFilter($iterator, $this->searchMode);
        }

        if($this->matches) {
            $iterator = new MatchFilter($iterator, $this->matches);
        }

        return $iterator;
    }

    public function getIterator()
    {

        $iterators = new \AppendIterator;

        foreach($this->dirs as $dir) {

            $iterators->append($this->processDirFilters($dir));
        } 

        return $iterators;
    }

    public function getFileArray()
    {
        return iterator_to_array($this->getIterator());
    }

}