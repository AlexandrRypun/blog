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

}