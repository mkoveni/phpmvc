<?php

namespace Mkoveni\Lani\DI;

interface ContainerInferface 
{
    public function get($key);

    public function set($key, callable $value);

    public function share($key, callable $value);

    public function has($key);

    public function registerProvider($provider);
}