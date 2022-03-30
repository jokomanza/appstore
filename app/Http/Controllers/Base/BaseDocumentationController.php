<?php

namespace App\Http\Controllers\Base;

abstract class BaseDocumentationController extends BaseController
{
    public function index()
    {
        return view($this->getUserType() . '.documentation.index');
    }
}
