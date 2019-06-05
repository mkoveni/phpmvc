<?php

namespace App\Core\Providers;

use App\Core\Http\RequestInterface;
use App\Core\Http\Request;
use App\Core\Reflection\RFactory;
use App\Core\Router\Router;
use App\Core\DI\ContainerInferface;

class AppServiceProvider extends AbtractServiceProvider 
{
    protected $sharedServices = [
        Router::class => Router::class,
        RFactory::class => Rfactory::Class
    ];


    protected $services = [
        RequestInterface::class => Request::class
    ];

    public function register()
    {
    
        $c = $this->getContainer();

        $this->registerSharedServices($c);

        $this->registerServices($c);

    }

    protected function registerSharedServices(ContainerInferface $containerInferface)
    {
        foreach($this->sharedServices as $alias => $concrete)
        {
            $containerInferface->share($alias, function() use($concrete){

                return new $concrete;
            });
        }
    }

    protected function registerServices(ContainerInferface $containerInferface)
    {
        foreach($this->services as $alias => $concrete)
        {
            $containerInferface->share($alias, function() use($concrete){
                
                return new $concrete;
            });
        }
    }
}