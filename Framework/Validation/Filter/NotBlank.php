<?php
/**
 * Create class NotBlank
 */

namespace Framework\Validation\Filter;


class NotBlank extends AFilter{


    public function check($value){
        if ($value == false) $this->error = 'should not be blank';

        return ($this->error)?$this->error:false;
    }
}