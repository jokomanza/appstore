<?php

use App\Models\App;
use App\Models\Permission;
use App\Notifications\NewReportNotification;
use Illuminate\Support\Facades\Auth;


if (!function_exists('isClientDeveloper')) {
    function isClientDeveloper()
    {
        return Auth::user()->isDeveloperOf(Permission::whereHas('app', function ($q) {
            $q->where('package_name', 'com.quick.quickappstore');
        })->first());
    }
}

if (!function_exists('isClientApp')) {
    function isClientApp(App $app)
    {
        return $app->package_name == config('app.client_package_name');
    }
}

if (!function_exists('hasUnreadNotification')) {
    function hasUnreadNotification()
    {
        return Auth::user()->unreadNotifications
                ->where('type', NewReportNotification::class)->first() != null;
    }
}