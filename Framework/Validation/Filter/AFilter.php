<?php
/**
 * Create abstract class AFilter
 */

namespace Framework\Validation\Filter;


abstract class AFilter
{
    protected $error = null;

    abstract public function check($val);
}