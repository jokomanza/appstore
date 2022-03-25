<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseAppController;
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
class UserAppController extends BaseAppController
{
    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        parent::__construct($appRepository, $appService);
        $this->middleware('auth:user');
    }
    function getView()
    {
        return 'user';
    }
}
