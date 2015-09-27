<?php
/**
 * Created by PhpStorm.
 * User: sash
 * Date: 27.09.15
 * Time: 22:06
 */

namespace Framework\Exception;


class HttpNotFoundExeption extends \Exception{

    public function __construct($name){
        echo 'not found '.$name;
    }

}