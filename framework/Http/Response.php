<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements ResponseInterface
{
    use MessageTrait;


    protected $statusCode;

    protected $message = 'Http Message';

    public function __construct($status = 200, $headers = [], $body = null, $message = '', $protocolVersion = '1.1')
    {
        $this->headers = $headers;
        $this->statusCode = $status;
        $this->message = $message;
        $this->version = $protocolVersion;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }



    public function withJson($data, $status = 200)
    {
        $clone = clone $this;

        $response = $clone->withBody(Stream::create(json_encode($data, JSON_PRETTY_PRINT)));

        if($response->getBody() === false) {
            throw new \RuntimeException(json_last_error_msg(), json_last_error());
        }

        $response = $response->withHeader('Content-Type', 'application/json');

    
        return $response->withStatus($status);

    }

    public function withRedirect(string $url, $status = null)
    {
        $response = $this->withHeader('Location', $url);

        $status = $status ?? 200;

        return $response->withStatus($status);
    }

    public function withStatus($status, $message = '')
    {
        $clone = clone $this;

        $clone->statusCode = $status;

        $clone->message = $message;

        return $clone;
    }

    public function getReasonPhrase()
    {
        return $this->message;
    }

    public function __toString()
    {
        $eol = '\r\n';

        $response = sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->message);
        
        $response .= $eol;

        foreach($this->headers as $name => $value)
        {
            $response .= sprintf('s%: %s', $name, $value) . $eol;
        }


        $response .= $eol;

        $response .= (string) $this->getBody();

        return $response;
    }
    
}