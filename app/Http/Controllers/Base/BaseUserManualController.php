<?php

namespace App\Http\Controllers\Base;

use Illuminate\Support\Facades\File;

abstract class BaseUserManualController extends BaseController
{
    public function index()
    {
        $userManual = null;

        if (File::exists(public_path('storage/user_manual.pdf'))) $userManual = asset('/storage/user_manual.pdf');

        return view($this->getUserType() . '.manual.index', compact('userManual'));

    }
}
