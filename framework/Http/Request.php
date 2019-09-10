<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\UriInterface;
use Psr\Http\Message\ServerRequestInterface;

class Request implements ServerRequestInterface
{
    use MessageTrait;

    protected $attributes = [];

    protected $params = [];

    protected $body;

    /**
     * Undocumented variable
     *
     * @var \Psr\Http\Message\UriInterface
     */
    protected $uri;

    protected $method;

    protected $target;

    public function __construct($server)
    {
        $server = array_change_key_case($server);

        $this->params['server'] = $server;

        $this->method = $server['request_method'];
        
        $this->uri = Uri::fromServerParams($server);
    }
    
    public function getServerParams()
    {
        return $this->getParams('server');
    }

    public function getCookieParams()
    {
        return  $this->getParams('cookie');
    }

    /**
     * {@inheritDoc}
     */
    public function withCookieParams(array $cookies)
    {
        return $this->withParams('cookie', $cookies);
    }

    /**
     * {@inheritDoc}
     */
    public function getQueryParams()
    {
        return $this->getParams('query');
    }

    /**
     * {@inheritDoc}
     */
    public function withQueryParams(array $query)
    {
        return $this->withParams('query', $query);
    }

    /**
     * {@inheritDoc}
     */
    public function getUploadedFiles()
    {
        return $this->getParams('file');
    }

    /**
     * {@inheritDoc}
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return $this->withParams('file', $uploadedFiles);
    }

    /**
     * {@inheritDoc}
     */
    public function getParsedBody()
    {
        return $this->body;
    }

    /**
     * {@inheritDoc}
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;

        $clone->body = $data;

        return $clone;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes[$name] ?? $default;
    }

    /**
     * {@inheritDoc}
     */
    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;

        return $clone;
    }

    
    /**
     * {@inheritDoc}
     */
    public function withoutAttribute($name)
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);

        return $clone;
    }



    /**
     * This is not ps7 implementation
     *
     * sets parameters for the request
     * @return void
     */
    protected function setParams($type, array $params)
    {
        $this->params[$type] = $params;
    }

    /**
     * This is not ps7 implementation
     *
     * gets parameters for the request
     * @return void
     */
    protected function getParams($type)
    {
        return $this->params[$type] ?? [];
    }

    protected function withParams($type, array $params)
    {
        $clone = clone $this;
        $clone->setParams($type, $params);

        return $clone;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function withMethod($method)
    {
        $clone = clone $this;

        $clone->method = $method;

        return $clone;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        $clone->uri = $uri;

        return $clone;
    }

    public function getRequestTarget()
    {
        $target = $this->target ?? '';

        if(!empty($this->uri->getPath())) {
            $target = $this->uri->getPath();
        }
        else {
            $target = '/';
        }

        if(!empty($this->uri->getQuery())) {
            $target .= "?{$this->uri->getQuery()}";
        }

        return $target;
    }

    public function withRequestTarget($requestTarget)
    {
        if(strpos($requestTarget, ' ') === false) {

            throw new \InvalidArgumentException('The provided target is invalid.');
        }

        $clone = clone $this;
        $clone->target = $requestTarget;

        return $clone;
    }

    public static function fromGlobals($server, $request)
    {
        return new static($server);
    }

    
}