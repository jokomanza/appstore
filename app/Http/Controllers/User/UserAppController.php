<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Base\BaseAppController;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;

/**
 * Class App Controller
 *
 * @property AppRepositoryInterface $applicationRepository
 * @property AppServiceInterface $applicationService
 *
 * @package App\Http\Controllers
 */
class UserAppController extends BaseAppController
{
    public function __construct(AppRepositoryInterface $applicationRepository, AppServiceInterface $applicationService)
    {
        parent::__construct($applicationRepository, $applicationService);
        $this->middleware('auth:user');
    }

    function getUserType()
    {
        return 'user';
    }
}
