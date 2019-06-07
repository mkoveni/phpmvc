<?php

namespace Mkoveni\Lani\Providers;

use Mkoveni\Lani\DI\Container;
use Mkoveni\Lani\DI\ContainerInferface;

abstract class AbstractServiceProvider
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
