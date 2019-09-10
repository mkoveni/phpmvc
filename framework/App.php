<?php

namespace Mkoveni\Lani;

use Mkoveni\Lani\Exceptions\ClassNotFoundException;
use Mkoveni\Lani\DI \ {
    Container,
    Dependency
};
use Mkoveni\Lani\Routing \ {
    Route,
    Router,
    Middleware\MiddlewareAwareTrait
};
use Mkoveni\Lani\Reflection \ {
    RFactory,
    AbstractReflector,
    RClass
};
use Mkoveni\Lani\Filesystem\Filesystem;
use Mkoveni\Lani\Exceptions\FileNotFoundException;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;

class App implements RequestHandlerInterface
{
    use MiddlewareAwareTrait;

    protected $container;

    protected $rootDir;


    /**
     * Filesystem instance
     *
     * @var Filesystem
     */
    protected $filesystem;

    protected $serviceProviders = [
        \Mkoveni\Lani\Providers\ConfigServiceProvider::class,
        \Mkoveni\Lani\Providers\AppServiceProvider::class,
        \Mkoveni\Lani\Providers\ValidationServiceProvider::class
    ];

    protected $aliases = [
        \Mkoveni\Lani\Facades\Router::class,
        \Mkoveni\Lani\Facades\Config::class
    ];

    public function __construct(string $rootDir)
    {
        $this->container = Container::getInstance();

        $this->filesystem = $this->container->get(Filesystem::class);

        if (!$this->filesystem->exists($rootDir)) {

            throw new FileNotFoundException(sprintf('The specified root path %s is not valid.', $rootDir));
        }

        $this->rootDir = $rootDir;

        $this->registerPaths();

        $this->registerProviders();

        $this->registerAliases();

        $this->loadRoutes();
    }

    public function getContainer()
    {
        return $this->container;
    }

    protected function registerAliases()
    {
        foreach ($this->aliases as $alias) {
            $reflector = $this->getReflector('class', $alias);

            if ($reflector instanceof RClass) {

                $alias::setContainer($this->container);

                class_alias($alias, $reflector->getShortName());
            }
        }
    }

    protected function registerProviders()
    {
        foreach ($this->serviceProviders as $provider) {
            $this->container->registerProvider($provider);
        }
    }

    protected function registerPaths()
    {
        $this->container->set('rootDir', function () {
            return $this->rootDir;
        });

        $this->container->set('configDir', function () {
            return $this->rootDir . 'config';
        });

        $this->container->set('routesDir', function () {
            return $this->rootDir . 'routes';
        });
    }

    protected function loadRoutes()
    {
        $files = Filesystem::getDirectoryScanner()
            ->files()
            ->matches('\.php$')
            ->searchDir(routesDir())
            ->getFileArray();

        foreach ($files as $file) {

            require_once $file;
        }
    }

    public function run(ServerRequestInterface $request)
    {
        try {

            $router = $this->container->get(Router::class);

            $route = $router->dispatch($request);

            $response = $this->process($route);

            if (!$response instanceof ResponseInterface) {

                throw new \RuntimeException(sprintf('Callable must return an instance of %s', ResponseInterface::class));
            }


            $middleware =  new class ($response) implements MiddlewareInterface
            {

                protected $response;

                public function __construct(ResponseInterface $response)
                {
                    $this->response = $response;
                }

                public function process(ServerRequestInterface $request, RequestHandlerInterface $handler):ResponseInterface
                {
                    return $this->response;
                }
            };

            $this->addMiddleToStack($middleware);

            $this->respond($this->handle($request));
        } catch (\Exception $ex) {

            throw $ex;
        }
    }



    protected function process(Route $route)
    {
        $handler = $route->getHandler();



        if ($handler instanceof \Closure) {

            $reflector = $this->getReflector('function', $handler);

            $params = $reflector->getClassArguments();

            $resolved = $params->map([$this, 'resolveFromContainer'])->toArray();

            return call_user_func_array($handler, array_merge($route->getData(), $resolved));
        }

        if (is_array($handler) && count($handler) === 2) {
            [$controller, $method] = $handler;


            if (!class_exists($controller)) {

                throw new ClassNotFoundException(sprintf('Controller %s Could not be found.', $controller));
            }

            $controller = $this->container->get($controller);

            if (!method_exists($controller, $method)) {
                throw new \BadMethodCallException(sprintf('%s Has no method %s', $controller, $method));
            }

            $reflector = $this->getReflector('method', [$controller, $method]);

            $params = $reflector->getClassArguments();

            $resolved = $params->map([$this, 'resolveFromContainer'])->toArray();

            return call_user_func_array([$controller, $method], array_merge($route->getData(), $resolved));
        }
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $middleware = current($this->middleware);

        next($this->middleware);

        if (!$middleware) {

            throw new \RuntimeException('The middleware stack is exhausted.');
        }

        return $middleware->process($request, $this);
    }

    protected function respond(ResponseInterface $response)
    {
        if(headers_sent()) {
            throw new \RuntimeException('The response has already been emitted.');
        }
        
        /*Emit the server protocol+ status code + phrase first*/


        header(sprintf('HTTP/%s: %d%s', 
            $response->getProtocolVersion(), 
            $response->getStatusCode(), 
            $response->getReasonPhrase())
        );

        /*Emit response headers*/

        
        foreach($response->getHeaders() as $name => $headerValues)
        {
            foreach($headerValues as $value)
            {
                header(sprintf('%s: %s', $name, $value));
            }
        }

         /*Emit response body*/

         echo $response->getBody();

    }


    public function resolveFromContainer(Dependency $dependency)
    {
        return $this->container->get($dependency->getClass());
    }


    /**
     * Undocumented function
     *
     * @param [type] $type
     * @param [type] $args
     * @return AbstractReflector
     */
    public function getReflector($type, $args)
    {
        return $this->container->get(RFactory::class)->make($type, $args);
    }
}
