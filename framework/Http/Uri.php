<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{

    protected $host;

    protected $fragment;

    protected $path;

    protected $query;

    protected $port;

    protected $scheme;

    protected $user;

    protected $password;

    protected const DEFAULT_SCHEMES = [
        'https' => 443,
        'http' => 80
    ];


    public function __construct($url ='')
    {
        if(!is_string($url)) {

            throw new \InvalidArgumentException(sprintf('The %s constructor only accepts a string argument', get_class($this)));
        }

        if($uri = parse_url($url))
        {
            $this->host = $uri['host'] ?? '';
            $this->path = $uri['path'] ?? '';
            $this->query = $uri['query'] ?? '';
            $this->scheme = $uri['scheme'] ?? '';
            $this->fragment = $uri['fragment'] ?? '';
            $this->port = $uri['port'] ?? '';
            $this->user = $uri['user'] ?? '';
            $this->password = $uri['pass'] ?? '';

            
        }
    }

    public function getAuthority()
    {
        $authority = '';

        if(!empty($this->host) && !empty($this->user) && !empty($this->password))
        {
            $authority = "{$this->user}@{$this->host}:{$this->password}";
        }
        else if(!empty($this->host) && !empty($this->user))
        {
            $authority = "{$this->user}@{$this->host}";
        }
        else {
            $authority = $this->host;
        }

        return $authority;
    }

    
    public function getHost()
    {
        return $this->host;
    }

    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $host;

        return $clone;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withFragment($fragment)
    {
        $clone = clone $this;
        $clone->fragment = $fragment;

        return $clone;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = $path;

        return $clone;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function withPort($port)
    {
        $clone = clone $this;
        $clone->port = $port;

        return $clone;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function withQuery($query)
    {
        $clone = clone $this;
        $clone->query = $query;

        return $clone;
    }

    public function getScheme()
    {
        return !empty($this->scheme) ? $this->scheme : [];
    }

    public function withScheme($scheme)
    {
        $clone = clone $this;
        $clone->scheme = $scheme;

        return $clone;
    }

    public function getUserInfo()
    {
        return !empty($this->user) && !empty($this->password) ? "{$this->user}:{$this->password}" : $this->user;
    }

    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->user = $user;
        $clone->password = $password;

        return $clone;
    }

    public static function fromServerParams($server) {
        $ssl = $server['https'] ?? 'off';

        $scheme = $ssl === 'on' ? 'https': 'http';
        $host = $server['http_host'] ?? '';
        $path = $server['request_uri'] ?? '';
        $port = $server['server_port'] ?? '';

        
        return new static("{$scheme}://{$host}:{$port}{$path}");

    }

    public function __toString()
    {
        return '';
    }
}