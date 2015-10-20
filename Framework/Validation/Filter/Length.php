<?php

namespace Framework\Validation\Filter;


class Length{

    public function __construct($min, $max){
       // echo "min->".$min.", max->".$max
        //return true;
    }

    public function check($obj){
        echo $obj;
    }

}