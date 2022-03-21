<?php

namespace App\Http\Controllers\Base;

use App\Models\App;
use App\User;
use Illuminate\Http\Request;
use App\Admin;
use App\Models\Report;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

abstract class HomeBaseController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $recentApps = App::orderBy('updated_at', 'DESC')->limit(5)->get();
        $appsCount = App::count();
        $developersCount = User::count();
        $adminsCount = Admin::count();
        $errorsCount = Report::count();
        $isClientDeveloper = Auth::user()->isDeveloperOf(Permission::whereHas('app', function ($q) {
            $q->where('package_name', 'com.quick.quickappstore');
        })->first());

        return view(
            $this->getView() . '.home',
            compact('recentApps', 'appsCount', 'developersCount', 'adminsCount', 'errorsCount', 'isClientDeveloper')
        );
    }
}