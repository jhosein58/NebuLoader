<?php

namespace HelloWorld;
use HelloWorld\Parser\DataParser;
class HelloWorld
{
    private $data;
    public function __construct(){
        $parser = new DataParser();
        $this->data = $parser->parseData();
    }
    public function HelloWorld(): void{
        echo $this->data[0];
    }
}