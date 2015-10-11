<?php

namespace Framework\Session;


class Session{

    public  $returnUrl;

    public function addToSess($key, $value){
        $_SESSION[$key] = $value;
    }

    public function get($key){
        return $_SESSION[$key];
    }

    public function delFromSess($key){
        unset ($_SESSION[$key]);
    }

    public function setReturnUrl($url){
        if (!strpos($url, 'login')) $this->addToSess('returnUrl',$url);
        $this->returnUrl = $this->get('returnUrl');
    }

}