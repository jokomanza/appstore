<?php

namespace App\Http\Controllers;

use App\Models\App;
use App\User;
use Illuminate\Http\Request;
use App\Admin;
use App\Models\Report;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
        
        return view(
            'home',
            compact('recentApps', 'appsCount', 'developersCount', 'adminsCount', 'errorsCount')
        );
    }
}
