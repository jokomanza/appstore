<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseAppVersionController;
use App\Interfaces\AppVersionServiceInterface;

class AdminAppVersionController extends BaseAppVersionController
{
    public function __construct(AppVersionServiceInterface $service)
    {
        parent::__construct($service);
        $this->middleware('auth:admin');
    }

    function getView()
    {
        return 'admin';
    }
}
