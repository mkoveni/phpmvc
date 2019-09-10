<?php

namespace Mkoveni\Lani\Http;

use Psr\Http\Message\StreamInterface;

class Stream implements StreamInterface
{
    protected $stream;

    protected $size;

    protected $isWritable;

    protected $isReadable;

    protected $isSeekable;

    protected $meta;

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

            $data = self::getResourceFromString($data);
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

        $this->meta = stream_get_meta_data($this->stream);

        $this->isReadable = $this->readTypes[$this->meta['mode']];
        $this->isWritable = $this->writeTypes[$this->meta['mode']];
        $this->isSeekable = $this->meta['seekable'];
    }
    
    public function detach()
    {
        $stream = $this->stream;
        $this->stream = null;
        $this->meta = null;
        $this->size = null;
        
        return $stream;
    }

    public function eof()
    {
        if($this->stream){
            return feof($this->stream);
        }

        return false;
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

    public function getSize()
    {
        if($this->size) {
            return $this->size;
        }

        if($this->stream) {
            
            if(($stats = fstat($this->stream)) && ($this->size = $stats['size']))
            {
                return $this->size;
            }
        }

        return null;
    }

    public function tell()
    {
        if($this->stream){
            return ftell($this->stream);
        }

        return false;
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

    public function getMetadata($key = null)
    {
        if($key && isset($this->meta[$key])) {

            return $this->meta[$key];
        }

        if(!$this->stream) {
            
            return [];
        }

        return $this->meta = stream_get_meta_data($this->stream);
    }

    
    protected static function getResourceFromString(string $data)
    {
        $resource = @fopen('php://temp', 'rw+');

        @fwrite($resource, $data);

        return $resource;
    }

    public function write($string)
    {
        
    }

    public function read($length)
    {
        
    }

    public function rewind()
    {
        if($this->stream) {

            $this->seek(0);
        }
    }

    public function __toString()
    {
        if($this->stream) {

            $this->seek(0);

            return (string)stream_get_contents($this->stream);
        }

        return '';
    }

}
