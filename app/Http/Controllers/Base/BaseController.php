<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    /**
     * Return user type for this controller
     *
     * @return string user type
     */
    abstract function getUserType();
}
