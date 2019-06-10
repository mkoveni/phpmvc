<?php

namespace Mkoveni\Lani\Config;

use Mkoveni\Lani\Config\Loaders\Loader;
use Mkoveni\Lani\Collection\Arr;

class Config 
{
    protected $config = [];

    protected $cached = [];

    public function fromLoaders(array $loaders)
    {
        foreach($loaders as $loader) {

            if($loader instanceof Loader) {

                $this->config = array_merge($this->config, $loader->parse());
            }
        }


        return $this;
    }

    public function get($key, $default = null)
    {
        if($this->isCache($key)) {

            return $this->fromCache($key);
        }

        return $this->cache($key, Arr::get($this->config, $key, $default));
    }

    protected function cache($key, $value) {

        return $this->cached[$key] = $value;
    }

    protected function isCache($key)
    {
        return Arr::exists($this->cached, $key);
    }

    protected function fromCache($key)
    {
        return Arr::get($this->cached, $key, null);
    }

    public function set($key, $value) {

        $this->config[$key] = $value;
    }
}