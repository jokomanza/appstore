<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\HomeBaseController;
use App\Http\Controllers\Controller;
use App\Models\App;
use App\User;
use Illuminate\Http\Request;
use App\Admin;
use App\Models\Report;

class UserHomeController extends HomeBaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    function getView()
    {
        return 'user';
    }
}
