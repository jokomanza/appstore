<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\HomeBaseController;

class AdminHomeController extends HomeBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    function getView()
    {
        return 'admin';
    }
}
