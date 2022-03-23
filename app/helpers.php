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

if (!function_exists('isClientApp')) {
    function isClientApp(\App\Models\App $app)
    {
        return $app->package_name == config('app.client_package_name');
    }
}