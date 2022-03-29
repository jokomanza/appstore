<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\NotificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminNotificationController extends NotificationController
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    function getView()
    {
        return 'admin';
    }
}
