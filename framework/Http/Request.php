<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\ServerRequestInterface;

class Request implements ServerRequestInterface
{
    protected $attributes = [];

    protected $params = [];

    protected $body;
    
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
}