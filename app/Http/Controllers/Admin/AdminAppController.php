<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseAppController;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;

class AdminAppController extends BaseAppController
{
    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        parent::__construct($appRepository, $appService);
        $this->middleware('auth:admin');
    }

    function getView()
    {
        return 'admin';
    }
}
