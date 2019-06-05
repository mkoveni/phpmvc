<?php

namespace App\Core\Providers;

use App\Core\DI\Container;
use App\Core\DI\ContainerInferface;

abstract class AbtractServiceProvider
{
    public abstract function register();

    /**
     * gets the current container instance
     *
     * @return ContainerInferface
     */
    protected function getContainer(): ContainerInferface
    { 
        return Container::getInstance();
    }
}
