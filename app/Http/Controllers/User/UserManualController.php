<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseUserManualController;

class UserManualController extends BaseUserManualController
{

    public function __construct()
    {
        $this->middleware('auth:user');
    }

    function getUserType()
    {
        return 'user';
    }
}
