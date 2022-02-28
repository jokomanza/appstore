<?php

namespace App\Http\Controllers;

use App\Models\App;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recentApps = App::orderBy('updated_at', 'DESC')->limit(5)->get();
        $appsCount = App::count();
        $developersCount = 2;
        return view(
            'home',
            [
                'recentApps' => $recentApps,
                'appsCount' => $appsCount,
                'developersCount' => $developersCount,
            ]
        );
    }
}
