<?php

namespace Mkoveni\Lani\Http;

class Response 
{
    protected $body;

    protected $statusCode;

    protected $headers = [];

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function withJson($data)
    {
        $this->withHeader('Content-Type', 'application/json');

        $this->setBody(json_encode($data));

        return $this;
    }

    public function withStatus($status = 200)
    {
        $this->statusCode = $status;

        return $this;
    }

    public function withHeader($key, $value)
    {
        $this->headers[$key] = $value;

        return $this;
    }

    public function send()
    {
    }

    
}