<?php

namespace HelloWorld\Parser;
use HelloWorld\DataCenter\MainDataBase;
class DataParser
{
    private $data;
    public function __construct(){
        $this->data = new MainDataBase;
    }
    public function parseData(){
        $res = [];
        foreach ($this->data->data as $data){

            $res[] = hex2bin($data);
        }
        return $res;
    }
}