<?php
/**
 * Created by PhpStorm.
 * User: sash
 * Date: 24.10.15
 * Time: 14:05
 */

namespace Framework\Validation\Filter;


abstract class AFilter
{
    protected $error = null;

    abstract public function check($val);
}