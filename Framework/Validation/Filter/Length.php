<?php

namespace Framework\Validation\Filter;


class Length extends AFilter{

    private $min;
    private $max;

    public function __construct($min, $max){
       $this->min = $min;
       $this->max = $max;
    }

    public function check($str){
        if ($this->min>strlen($str)){
            $this->error = 'shoud have at least '.$this->min.' characters';
        }
        if ($this->max<strlen($str)){
            $this->error = 'shoud have a maximum of '.$this->min.' characters';
        }

        return ($this->error)?$this->error:false;
    }

}