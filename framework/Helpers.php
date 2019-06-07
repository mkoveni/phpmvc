<?php
use Mkoveni\Lani\Collection\Collection;

if(!function_exists('collect'))
{
    /**
     * return a new collection instance
     *
     * @param array $data
     * @return Collection
     */
    function collect(array $data): Collection
    {
        return new Collection($data);
    }
}