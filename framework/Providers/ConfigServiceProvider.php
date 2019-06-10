<?php

namespace Mkoveni\Lani\Providers;

use Mkoveni\Lani\Config\Config;
use Mkoveni\Lani\Filesystem\Filesystem;
use Mkoveni\Lani\Config\Loaders\ArrayLoader;

class ConfigServiceProvider extends AbstractServiceProvider
{
    public function register()
    {
        $c = $this->getContainer();

        $c->share(Config::class, function(){

            $configFiles = Filesystem::getDirectoryScanner()
            ->files()
            ->matches('\.php$')
            ->searchDir(configDir())
            ->getFileArray();

            $configFiles = $this->getFilenames($configFiles);

            $arrayLoader = new ArrayLoader($configFiles);

            $config = new Config;

            $config->fromLoaders([$arrayLoader]);

            return $config;
        });
    }

    /**
     * Undocumented function
     *
     * @param \SplFileInfo[] $files
     * @return array
     */
    protected function getFilenames($files)
    {
        return collect($files)->map(function(\SplFileInfo $file){
            return [str_replace('.' . $file->getExtension(), '', $file->getFilename()) => $file->getPathname()];
        })->toArray(); 
    }
}