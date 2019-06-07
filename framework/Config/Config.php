<?php

namespace Mkoveni\Lani\Config;

use Mkoveni\Lani\Config\Parsers\ParserInterface;
use Mkoveni\Lani\Exceptions\FileNotFoundException;

class Config 
{
    /**
     * Parser instance
     *
     * @var ParserInterface
     */
    protected $parser;

    protected $config = [];

    public function __construct(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function load($file)
    {
        if(!file_exists($file)) {

            throw new FileNotFoundException("The Configuration file '$file' could not be found.");
        }

        $this->config = array_merge($this->config,
            $this->parser->parse($file)
        );
    }

    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function get($key, $default = null)
    {

    }
}
