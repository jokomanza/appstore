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

if (!function_exists('hasUnreadNotification')) {
    function hasUnreadNotification()
    {
        return Auth::user()->unreadNotifications
                ->where('type', NewReportNotification::class)->first() != null;
    }
}

if (!function_exists('isClientApp')) {
    function isClientApp(App $app)
    {
        return $app->package_name == config('app.client_package_name');
    }
}

if (!function_exists('loggedAsAdmin')) {
    /**
     * @return bool
     */
    function loggedAsAdmin()
    {
        try {
            return Auth::guard('admin')->check();
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('loggedAsUser')) {
    /**
     * @return bool
     */
    function loggedAsUser()
    {
        try {
            return Auth::guard('user')->check();
        } catch (Exception $e) {
            return false;
        }
    }
}

if (!function_exists('put_permanent_env')) {
    function put_permanent_env($key, $value)
    {
        $path = app()->environmentFilePath();

        $escaped = preg_quote('=' . env($key), '/');

        file_put_contents($path, preg_replace(
            "/^{$key}{$escaped}/m",
            "{$key}={$value}",
            file_get_contents($path)
        ));
    }
}