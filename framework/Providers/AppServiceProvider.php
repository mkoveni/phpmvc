<?php

namespace Mkoveni\Lani\Providers;

use Mkoveni\Lani\Routing\Router;
use Mkoveni\Lani\Reflection\RFactory;
use Mkoveni\Lani\DI\ContainerInferface;

use Mkoveni\Lani\Http\{Request, Response};
use Psr\Http\Message\ServerRequestInterface;

class AppServiceProvider extends AbstractServiceProvider 
{
    protected $sharedServices = [
        Router::class => Router::class,
        RFactory::class => Rfactory::Class,
    ];

    
    protected $services = [
        ServerRequestInterface::class => Request::class,
        ResponseInterface::class => Response::class
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