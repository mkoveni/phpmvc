<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    protected $body;

    protected $headers = [];

    protected $protocolVersion = '1.1';

    public function getBody()
    {
        if(!$this->body) {
            $this->body = Stream::create('');
        }

        return $this->body;
    }

    public function withBody(StreamInterface $body) {
        $clone  = clone $this;
        $clone->body = $body;

        return $clone;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? [];
    }

    public function setHeader($name, $value)
    {
        $name = strtolower($name);

        $this->headers[$name] = $this->hasHeader($name) ? 
                array_merge($this->headers[$name], (array) $value) : (array) $value;
    }

    public function getHeaderLine($name)
    {
        return implode(',',$this->getHeader($name));
    }

    public function hasHeader($name)
    {
        return isset($this->headers[$name]);
    }

    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;

        $clone->setHeader($name, $value);

        return $clone;
    }

    public function withHeader($name, $value)
    {
        $clone = clone $this;

        $clone->setHeader($name, $value);

        return $this;
    }


    public function withoutHeader($name)
    {
        $clone = clone $this;

        $name = strtolower($name);

        if($clone->hasHeader($name)) {
            $headers = $clone->getHeaders();
            unset($headers[$name]);
            $clone->setHeader($name, $headers);
        }

        return $clone;

    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version)
    {
        $clone = clone $this;

        $clone->protocolVersion = $version;

        return $clone;
    }
}
