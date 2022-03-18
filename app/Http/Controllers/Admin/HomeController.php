<?php

namespace App\Http\Controllers\Admin;

use App\Models\App;
use App\User;
use Illuminate\Http\Request;
use App\Admin;
use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
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
            'home.admin',
            compact('recentApps', 'appsCount', 'developersCount', 'adminsCount', 'errorsCount', 'isClientDeveloper')
        );
    }
}
