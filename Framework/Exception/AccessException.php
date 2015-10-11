<?php

namespace Framework\Exception;


class AccessException extends \Exception{

    public function __construct($msg){
        echo $msg;
    }
}