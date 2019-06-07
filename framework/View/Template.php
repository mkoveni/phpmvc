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

    public function render($template, array $data)
    {
        return $this->compile($template, $data);
    }

    public function compile($template, $data)
    {
        
        try {

          $content = $this->getTemplateContent($template);

          if($extend = $this->getExtend($content))
          {
              $extend = $this->getTemplateContent($extend);

              $sections = $this->getSections($content);

              $this->layoutBlocks($extend);
          }

          if(count($data)) {

            var_dump($data);
          }

        }

        catch(\Exception $ex) {
            
        }

       return $content;
    }

    protected function getTemplateContent($templateUrl)
    {
        return $this->filesystem->get($this->templatesPath . $templateUrl);
    }

    protected function getExtend($templateData)
    {
        if(preg_match("/@extends\('([a-z\.]+)'\)/", $templateData, $matches))
        {
            return $matches[1];
        }

        return null;
    }

    protected function getSections($content)
    {
        if(preg_match("#\{%\s+block\s+[a-z]+\s+%\}(.*)\{%\s+endblock\s+%\}#ixs", $content, $matches)) {

            var_dump($matches[1]);

            // /return ['section' => $matches[1][0], 'content' => $matches[2][0]];
        }

        return [];
    }

    public function layoutBlocks($layout)
    {
        if(preg_match_all("#\{%\s+block\s+([a-z]+)\s+%\}\s*\{%\s+endblock\s+%\}#ixs", $layout, $matches)) {

            return $matches[1];
        }

        return [];
    }
}