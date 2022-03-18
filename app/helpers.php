<?php
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;



if (!function_exists('isClientDeveloper')) {
    function isClientDeveloper()
    {
        return Auth::user()->isDeveloperOf(Permission::whereHas('app', function ($q) {
            $q->where('package_name', 'com.quick.quickappstore');
        })->first());
    }
}