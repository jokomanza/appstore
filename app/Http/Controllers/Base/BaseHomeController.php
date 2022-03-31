<?php

namespace App\Http\Controllers\Base;

use App\Admin;
use App\Models\App;
use App\Models\AppVersion;
use App\Models\Report;
use App\User;
use Illuminate\View\View;

abstract class BaseHomeController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $recentApps = App::orderBy('updated_at', 'DESC')->limit(3)->get();
        $appsCount = App::count();
        $usersCount = User::count();
        $adminsCount = Admin::count();
        $errorsCount = Report::count();

        return view(
            $this->getUserType() . '.home',
            compact('recentApps', 'appsCount', 'usersCount', 'adminsCount', 'errorsCount')
        );
    }


    public function getReportsChart()
    {
        $errors = (new Report)->getChartData();

        return response()->json($errors);
    }

    public function getAppsChart()
    {
        $errors = (new App)->getChartData();

        return response()->json($errors);
    }

    public function getVersionsChart()
    {
        $errors = (new AppVersion)->getChartData();

        return response()->json($errors);
    }
}
