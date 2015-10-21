<?php

namespace Framework\Security;


use Framework\DI\Service;

class Security{


    public function setUser($user){
        Service::get('session')->addToSess('user', $user);
    }

    public function isAuthenticated(){
        return !empty($_SESSION['user']);
    }

    public function clear(){
        Service::get('session')->delFromSess('user');
    }

    public function generateToken(){
        $token = md5(Service::get('session')->getSessID());
        setcookie('token', $token);
        return $token;
    }

    public function checkToken(){
        $token = (Service::get('request')->post('token'))?Service::get('request')->post('token'):null;
        if(!is_null($token)){
            return ($token == $_COOKIE['token'])?true:false;
        }else{
            return true;
        }

    }

}