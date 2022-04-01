<?php

namespace App\Http\Controllers\Base\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

abstract class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the applicationlication and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applicationlications.
    |
    */

    use AuthenticatesUsers;

    protected function username()
    {
        return 'registration_number';
    }


}
