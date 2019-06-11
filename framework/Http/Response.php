<?php

namespace Mkoveni\Lani\Http;

class Response implements ResponseInterface
{
    protected $body;

    protected $statusCode;

    protected $headers = [];

    protected $version = '1.0';

    protected $message = 'Http Message';

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

    public function withBody(string $body){

        $clone = clone $this;

        $clone->setBody($body);

        return $clone;
    }

    public function withJson($data, $status = 200)
    {
        $response = $this->withBody(json_encode($data));

        

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

    public function withStatus(int $status, string $message = '')
    {
        $clone = clone $this;

        $clone->statusCode = $status;

        $clone->message = $message;

        return $clone;
    }

    public function withHeader($name, $value)
    {
        $clone = clone $this;

        $clone->headers[$name] = $value;

        header(sprintf('%s: %s', $name, $value));

        return $clone;
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