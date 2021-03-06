<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Base\Auth\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLoginController extends LoginController
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:user')->except('logout');
    }

    public function showLoginForm()
    {
        return view('user.auth.login');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        return redirect('/');
    }

    protected function guard()
    {
        return Auth::guard('user');
    }
}
