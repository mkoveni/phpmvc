<?php

 namespace App\Core\DI;

use App\Core\Exceptions\ClassNotFoundException;

class Dependency 
 {
     protected $class;

     protected $name;

     public function __construct($name, $class)
     {
        $this->setClass($class);

        $this->setName($name);
     }

     public function setClass(?string $class)
     {
        if($class && !(class_exists($class) || interface_exists($class)))
            throw new ClassNotFoundException(sprintf('The class %s could not be found.',  $class));

        $this->class = $class;
     }

     public function setName(string $name)
     {
        $this->name = $name;
     }

     public function getName()
     {
         return $this->name;
     }

     public function getClass()
     {
         return $this->class;
     }
 }