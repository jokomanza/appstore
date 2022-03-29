<?php

namespace App\Http\Controllers\Base;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

abstract class BaseUserManualController extends BaseController
{
    /**
     * @return Factory|Application|View
     */
    public function index()
    {
        $userManual = null;

        if (File::exists(public_path('storage/user_manual.pdf'))) $userManual = asset('/storage/user_manual.pdf');

        return view($this->getUserType() . '.manual.index', compact('userManual'));

    }
}
