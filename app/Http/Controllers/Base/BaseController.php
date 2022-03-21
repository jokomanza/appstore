<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    /**
     * Return base view for this controller
     *
     * @return string view name
     */
    abstract function getView();
}
