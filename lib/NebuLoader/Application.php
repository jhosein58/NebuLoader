<?php

namespace NebuLoader;

class Application
{
    public $loader;
    public $config;
    public function __construct(public $basePath)
    {
        $this->loader = new StaticLoader($this->basePath);
        $this->config = new Config($this->basePath);

        $this->loader->setPrefixes($this->config);
        $this->loader->setDependencies($this->config);
    }
    public function registerAutoloader($classLoader): void{
        spl_autoload_register($classLoader);
    }
}