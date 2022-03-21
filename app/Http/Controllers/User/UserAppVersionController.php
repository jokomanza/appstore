<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseAppVersionController;
use App\Http\Controllers\Base\Controller;
use App\Interfaces\AppVersionServiceInterface;

class UserAppVersionController extends BaseAppVersionController
{
    public function __construct(AppVersionServiceInterface $service)
    {
        parent::__construct($service);
        $this->middleware('auth:user');
    }

    function getView()
    {
        return 'user';
    }
}