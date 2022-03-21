<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Base\BaseAppVersionController;
use App\Models\AppVersion;
use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateAppVersionRequest;
use App\Interfaces\AppVersionServiceInterface;
use App\Http\Requests\CreateAppVersionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Controllers\Base\BaseController;

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
