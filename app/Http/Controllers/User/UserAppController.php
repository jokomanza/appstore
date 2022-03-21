<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseAppBaseController;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;

/**
 * Class App Controller
 * 
 * @property AppRepositoryInterface $appRepository
 * @property AppServiceInterface $appService
 * 
 * @package App\Http\Controllers
 */
class UserAppController extends BaseAppBaseController
{
    function getView()
    {
        return 'user';
    }
}
