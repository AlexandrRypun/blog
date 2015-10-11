<?php

namespace Framework\Exception;


use Framework\Response\Response;
use Framework\Renderer\Renderer;

class ServerErrorException extends \Exception{
    public function __construct($code, $message, $layout){
        $renderer = new Renderer($layout, array('message'=>$message, 'code'=>$code));
        $response = new Response($renderer->render());
        $response->send();
    }

}