<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseReportController;

class AdminReportController extends BaseReportController
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
