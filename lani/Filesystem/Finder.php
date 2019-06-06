<?php

namespace Mkoveni\Lani\Filesystem;

use Mkoveni\Lani\Filesystem\Filters\{FileFilter, DirectoryFilter};

class Finder implements \IteratorAggregate
{
    protected $iterator;

    protected $filters = [];

    protected $files = [];


    public static function create()
    {
        return new static;
    }

    public function in(string $dir)
    {
        $this->iterator = new \RecursiveDirectoryIterator($dir);

        $this->iterator->setFlags(\RecursiveDirectoryIterator::SKIP_DOTS | \RecursiveDirectoryIterator::UNIX_PATHS);

        return $this;
    }

    public function chainFilters()
    {
        foreach($this->filters as $filter) {
            
            if(is_array($filter)) {

                 $this->iterator = new $filter['type']($this->iterator, ...$filter['args']);

                 continue;
            }

            $this->iterator = new $filter($this->iterator);

            var_dump($this->iterator);
           
        }
    }

    protected function addIterableFilter($filter) {

        array_push($this->filters, $filter);
    }

    public function files()
    {
        $this->addIterableFilter(FileFilter::class);
    }


    public function directories()
    {
        $this->addIterableFilter(DirectoryFilter::class);

        return $this;
    }

    public function name(string $pattern)
    {
        
        return $this;
    }

    public function match()
    {
        
    }

    public function getIterator()
    {
        $this->chainFilters();

        return new \RecursiveIteratorIterator($this->iterator, \RecursiveIteratorIterator::SELF_FIRST);
    }
}