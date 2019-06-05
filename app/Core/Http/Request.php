<?php

namespace App\Core\Http;


class Request implements RequestInterface
{
    protected $headers = [];

    protected $data = [];


    public function __construct()
    {
        $this->init();

        $this->prepareData();
    }

    protected function init()
    {
        foreach($_SERVER as $key => $value) {

            $this->{$this->transformServerEntry($key)} = $value;
        }

        $this->headers = apache_request_headers();

    }

    public function all(): array
    {
        return $this->data ?? [];
    }

    public function getParam($key, $default = null): ?string
    {
        return $this->data[$key] ?? $default;
    }

    public function getHeader($key, $default = null): ?string
    {
        return $this->headers[$key] ?? $default;
    }

    public function getRequestMethod(): ?string
    {
        return $this->requestMethod;
    }

    public function isAjax(): bool
    {
        return ($header = $this->getHeader('Content-Type')) && $header === 'application/json';
    }

    protected function prepareData()
    {
        switch($this->requestMethod) {

            case 'GET':
                $this->data = $_GET;
                break;
            case 'POST':
                $this->data = $this->getPostData();
            default:
                $this->data = $this->getNoneStandardData();
            break;

        }
    }

    protected function getInput()
    {
        return file_get_contents('php://input');
    }

    protected function getPostData()
    {
        if($this->isAjax() && $stream = $this->getInput()) {
            
            return json_decode($stream,true);
        }

        return $_POST ?? ['name' => 'simon'];
    }

    protected function getNoneStandardData()
    {
        $data = [];

        if ($stream = $this->getInput()) {
            
            if($this->isAjax()) {

                return json_decode($stream, true);
            }

            return parse_str($stream, $data);
        }

        return $data;
        
    }

    protected function transformServerEntry(string $entry)
    {
        $entry = strtolower($entry);

        if(preg_match_all('#_[a-z]+#', $entry, $matches))
        {
            
            foreach($matches as $key => $match)
            {   
               foreach($match as $p) {

                $replacement = ucfirst(str_replace('_','', $p));

                $entry = str_replace($p, $replacement, $entry);

               }
            } 
        }

        return $entry;
    }
}