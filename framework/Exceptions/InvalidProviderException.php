<?php

namespace Mkoveni\Lani\Exceptions;

class InvalidProviderException extends \Exception
{
    protected $class;

    public function __construct($class)
    {
        $this->class = $class;

        $this->prepareMessage();
    }

    public function prepareMessage()
    {
        $this->message = sprintf('%s is not a valid service provider, service providers must be an instance of %s.',
            get_class($this->class), 
            \Mkoveni\Lani\Providers\AbtractServiceProvider::class
        );      
    }

}