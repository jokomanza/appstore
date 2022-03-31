<?php

namespace App\Exceptions;

abstract class BaseException extends \Exception
{
    /**
     * @var bool
     */
    public $shouldRedirectBack;
}