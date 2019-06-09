<?php

namespace Mkoveni\Lani\View;

use Mkoveni\Lani\Filesystem\Filesystem;

class Template
{
    protected $templatesPath;

    protected $filesystem;

    public function __construct($templatesPath, Filesystem $fs)
    {
        $this->templatesPath = $templatesPath;

        $this->filesystem = $fs;
    }
}