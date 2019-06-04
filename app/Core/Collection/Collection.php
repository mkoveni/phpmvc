<?php

namespace App\Core\Collection;

class Collection implements \Countable, \IteratorAggregate
{
    protected $items;

    public function __construct($items = [])
    {
        $this->items = $this->getItemAsArray($items);
    }

    public function add($item)
    {
        array_push($this->items, $item);

        return $this;
    }

    public function all()
    {
        return $this->items;
    }

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function first(callable $callable = null, $default = null)
    {
        return Arr::first($this->items, $callable) ?? $default;
    }

    public function last(callable $callable = null,$default = null)
    {
        
        return Arr::last($this->items, $callable) ?? $default;
    }

    public function each(callable $callable)
    {
        foreach($this->items as $key => $item)
        {
            call_user_func($callable, $key, $item);
        }
    }

    public function where(callable $callable)
    {
       return Arr::where($this->items, $callable);
    }

    public function map(callable $callable)
    {
        $keys = $this->keys()->all();

        return new static(array_map($callable, $this->items, $keys));
    }

    public function flattern()
    {
        return new static(Arr::flattern($this->items));
    }

    public function keys()
    {
        return new static(array_keys($this->items));
    }

    public function merge($items)
    {
        return new static(array_merge($this->items, $this->getItemAsArray($items)));
    }

    protected function getItemAsArray($items)
    {
        
        if($items instanceof Collection) {

            return $items->all();
        }

        if(!is_array($items)) {

            return [$items];
        }

        return $items;
    }

    public function toJson()
    {
        return json_encode($this->items);
    }

    public function count()
    {
        return count($this->items);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}