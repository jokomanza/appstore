<?php

namespace App\Http\Controllers\Base;

use App\Admin;
use App\Models\App;
use App\Models\AppVersion;
use App\Models\Permission;
use App\Models\Report;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $usersCount = User::count();
        $adminsCount = Admin::count();
        $errorsCount = Report::count();
        $isClientDeveloper = Auth::user()->isDeveloperOf(Permission::whereHas('app', function ($q) {
            $q->where('package_name', 'com.quick.quickappstore');
        })->first());


        return view(
            $this->getUserType() . '.home',
            compact('recentApps', 'appsCount', 'usersCount', 'adminsCount', 'errorsCount', 'isClientDeveloper')
        );
    }


    public function getReportsChart()
    {
        $errors = Report::select(DB::raw("TO_CHAR(DATE(created_at) :: DATE, 'Mon dd, yyyy') as x"), DB::raw('count(*) as y'))
            ->groupBy('x')
            ->get();

        return response()->json($errors);
    }

    public function getAppsChart()
    {
        $errors = App::select(DB::raw("TO_CHAR(DATE(created_at) :: DATE, 'Mon dd, yyyy') as x"), DB::raw('count(*) as y'))
            ->groupBy('x')
            ->get();

        return response()->json($errors);
    }

    public function getVersionsChart()
    {
        $errors = AppVersion::select(DB::raw("TO_CHAR(DATE(created_at) :: DATE, 'Mon dd, yyyy') as x"), DB::raw('count(*) as y'))
            ->groupBy('x')
            ->get();

        return response()->json($errors);
    }
}
