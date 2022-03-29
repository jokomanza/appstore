<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseUserManualController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserManualController extends BaseUserManualController
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    function getUserType()
    {
        return 'admin';
    }
}
