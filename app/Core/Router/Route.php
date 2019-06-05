<?php

namespace App\Core\Router;

use App\Core\Collection\Arr;

class Route 
{
    protected $methods = [];

    protected $handler;

    protected $uri;

    protected $data;

    public function __construct($uri, $handler, $methods = ['GET'])
    {
        $this->uri = $uri;

        $this->handler = $handler;

        $this->methods = $methods;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function allows($method)
    {
    
        return in_array($method, $this->methods);
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getData() 
    {
        return $this->data ?? [];
    }
}