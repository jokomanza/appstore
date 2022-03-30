<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class AdminSettingController extends Controller
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
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $userManual = null;
        $devStandard = null;
        $devGuide = null;

        if (File::exists(public_path('storage/user_manual.pdf'))) $userManual = asset('/storage/user_manual.pdf');
        if (File::exists(public_path('storage/android_development_standard.pdf'))) $devStandard = asset('/storage/android_development_standard.pdf');
        if (File::exists(public_path('storage/android_development_guide.pdf'))) $devGuide = asset('/storage/android_development_guide.pdf');

        return view('admin.setting.index', compact('userManual', 'devStandard', 'devGuide'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @return RedirectResponse
     */
    public function storeUserManual(StoreDocumentRequest $request)
    {
        $path = public_path('storage/user_manual.pdf');

        $message = File::exists($path) ? 'changed' : 'uploaded';

        if ($request->file('document')->move(public_path('/storage/'), 'user_manual.pdf')) {
            return back()->with('messages', ["Successfully $message the user manual"]);
        } else return back()->withErrors("Failed to upload user manual");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @return RedirectResponse
     */
    public function storeDevStandard(StoreDocumentRequest $request)
    {
        $path = public_path('storage/android_development_standard.pdf');

        $message = File::exists($path) ? 'changed' : 'uploaded';

        if ($request->file('document')->move(public_path('/storage/'), 'android_development_standard.pdf')) {
            return back()->with('messages', ["Successfully $message the android development standard"]);
        } else return back()->withErrors("Failed to upload android development standard");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDocumentRequest $request
     * @return RedirectResponse
     */
    public function storeDevGuide(StoreDocumentRequest $request)
    {
        $path = public_path('storage/android_development_guide.pdf');

        $message = File::exists($path) ? 'changed' : 'uploaded';

        if ($request->file('document')->move(public_path('/storage/'), 'android_development_guide.pdf')) {
            return back()->with('messages', ["Successfully $message the android development guide"]);
        } else return back()->withErrors("Failed to upload android development guide");
    }
}
