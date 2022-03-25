<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserDocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:user');
    }

    public function index()
    {
        return view('user.documentation.index');
    }
}
