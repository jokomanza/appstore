<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseHomeController;

class UserHomeController extends BaseHomeController
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

    function getUserType()
    {
        return 'user';
    }
}
