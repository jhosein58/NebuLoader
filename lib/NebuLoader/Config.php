<?php

namespace NebuLoader;
class Config
{
    private $configs;
    public string $name = 'nebuloader.json';
    public string $path;
    public function __construct(public $basePath)
    {
        $this->setPath();
        $this->setConfigs();

    }
    private function setPath(): void{
        $this->path = $this->basePath . DIRECTORY_SEPARATOR . $this->name;
    }
    public function get(){
        return json_decode(file_get_contents($this->path), true);
    }
    public function setConfigs(): void{
        $this->configs = $this->get();
    }
    public function update($configs): void{
        file_put_contents($this->path, json_encode($configs, JSON_PRETTY_PRINT));
    }
    public function addDependency($dependency): void{
        $this->setConfigs();
        $this->configs['require'][] = $dependency;
        $this->update($this->configs);
    }
    public function getPrefixes(){
        return $this->configs['psr-4'];
    }
    public function getDependencies(){
        return $this->configs['require'];
    }
}