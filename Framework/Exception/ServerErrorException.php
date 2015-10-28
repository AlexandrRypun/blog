<?php

namespace Framework\Exception;


use Framework\Response\Response;
use Framework\Renderer\Renderer;

class ServerErrorException extends \Exception{
    public $code;
    public $layout;
    public $message;

    public function __construct($code, $message, $layout){
        $this->code = $code;
        $this->message = $message;
        $this->layout = $layout;
    }

}