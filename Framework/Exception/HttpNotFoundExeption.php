<?php

namespace Framework\Exception;


class HttpNotFoundExeption extends \Exception{

    public function __construct($name){
        echo 'not found '.$name;
    }

}