<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseAppBaseController;
use App\Http\Requests\CreateAppRequest;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;
use App\Models\App;
use App\Repositories\AppRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\AppVersion;
use App\User;
use App\Models\Developer;
use App\Http\Requests\AddDeveloperRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Permission;
use User\User\Controller;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminAppController extends BaseAppBaseController
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
