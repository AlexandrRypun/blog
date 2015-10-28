<?php

namespace Framework\Session;


use Framework\DI\Service;

class Session{

    public  $returnUrl = null;

    public function addToSess($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key];
    }

    public function delFromSess($key){
        unset ($_SESSION[$key]);
    }

    public function getSessID(){
        return session_id();
    }

    public function setReturnUrl($url){

        if (!strpos($url, '/login')) {
            $this->addToSess('returnUrl',$url);
        }
        $this->returnUrl = $this->get('returnUrl');
    }

}