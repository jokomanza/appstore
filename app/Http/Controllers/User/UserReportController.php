<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseReportController;

class UserReportController extends BaseReportController
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
