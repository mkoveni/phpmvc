<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    protected $stream;

    protected $isWritable;

    protected $isReadable;

    protected $isSeekable;

    protected $writeTypes = [
        'w' => true, 'w+' => true, 'rw' => true, 'r+' => true, 'x+' => true,
        'c+' => true, 'wb' => true, 'w+b' => true, 'r+b' => true,
        'x+b' => true, 'c+b' => true, 'w+t' => true, 'r+t' => true,
        'x+t' => true, 'c+t' => true, 'a' => true, 'a+' => true,
    ];

    protected $readTypes = [
        'r' => true, 'w+' => true, 'r+' => true, 'x+' => true, 'c+' => true,
        'rb' => true, 'w+b' => true, 'r+b' => true, 'x+b' => true,
        'c+b' => true, 'rt' => true, 'w+t' => true, 'r+t' => true,
        'x+t' => true, 'c+t' => true, 'a+' => true,
    ];

    public static function create($data)
    {
        if(is_string($data)) {

            $data = $this->getResourceFromString($data);
        }

        return new static($data);
    }

    public function __construct($data)
    {
        if (!is_resource($data)) {
            throw new \InvalidArgumentException('The constructor argument must a be resource.');
        }

        $this->attach($data);
    }

    public function attach($stream)
    {
        $this->stream = $stream;

        $meta = stream_get_meta_data($this);

        $this->isReadable = $this->readTypes[$meta['mode']];
        $this->isWritable = $this->writeTypes[$meta['mode']];
        $this->isSeekable = $meta['seekable'];
    }
    
    public function detach()
    {
        $stream = $this->stream;
        $stream
    }

    public function getContents()
    {
        if($this->stream){

            return stream_get_contents($this->stream);
        }

        return '';
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if($this->isSeekable) {
            return fseek($this->stream, $offset, $whence);
        }

        return false;
    }

    public function close()
    {
        if($this->stream) {
            @fclose($this->stream);
        }
    }

    public function isWritable()
    {
        return $this->isWritable;
    }

    public function isReadable()
    {
        return $this->isReadable;
    }

    public function isSeekable()
    {
        return $this->isSeekable;
    }

    public function __toString()
    {
        if($this->stream) {

            $this->seek(0);

            return (string)stream_get_contents($this->stream);
        }

        return '';
    }

    protected function getResourceFromString(string $data)
    {
        $resource = @fopen('php://temp', 'rw+');

        @fwrite($resource, $data);

        return $resource;
    }
}
