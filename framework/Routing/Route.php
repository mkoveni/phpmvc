<?php

namespace Mkoveni\Lani\Routing;

class Route 
{
    protected $methods = [];

    protected $handler;

    protected $uri;

    protected $data;

    protected $name;

    public function __construct($uri, $handler, $methods = ['GET'], $name = '')
    {
        $this->uri = $uri;

        $this->handler = $handler;

        $this->methods = $methods;

        $this->$name = $name;
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

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->getName();
    }
}