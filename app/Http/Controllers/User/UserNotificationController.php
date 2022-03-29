<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\NotificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserNotificationController extends NotificationController
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    function getView()
    {
        return 'user';
    }
}
