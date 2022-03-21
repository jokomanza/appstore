<?php

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', function () {
    return 'Documentation page are under development';
})->name('docs');

//Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', 'Admin\Auth\AdminLoginController@showLoginForm')->name('show');
    Route::post('/login', 'Admin\Auth\AdminLoginController@login')->name('login');
    Route::post('/logout', 'Admin\Auth\AdminLoginController@logout')->name('logout');

    Route::get('/home', 'Admin\AdminHomeController@index')->name('home');

    Route::post('apps/datatables', 'Admin\AdminAppController@getDataTables')->name('app.datatables');
    Route::post('app/{packageName}/reports/datatables', 'Admin\AdminReportController@getDataTables')->name('app.report.datatables');
    Route::get('/apps', 'Admin\AdminAppController@index')->name('app.index');
    Route::get('/app', 'Admin\AdminAppController@create')->name('app.create');
    Route::post('/app', 'Admin\AdminAppController@store')->name('app.store');
    Route::put('/app/{packageName}', 'Admin\AdminAppController@update')->name('app.update');
    Route::post('/app/{packageName}/developer', 'Admin\AdminAppController@addPermission')->name('app.developer.store');
    Route::delete('/app/{packageName}/developer/{registrationNumber}', 'Admin\AdminAppController@removePermission')->name('app.developer.destroy');
    Route::delete('/app/{packageName}', 'Admin\AdminAppController@destroy')->name('app.destroy');
    Route::get('/app/{packageName}', 'Admin\AdminAppController@show')->name('app.show')->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::get('/app/{packageName}/edit', 'Admin\AdminAppController@edit')->name('app.edit');

    Route::get('/app/{packageName}/version', 'Admin\AdminAppVersionController@create')->name('version.create');
    Route::post('/app/{packageName}/version', 'Admin\AdminAppVersionController@store')->name('version.store');
    Route::put('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@update')->name('version.update');
    Route::delete('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@destroy')->name('version.destroy');
    Route::get('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@show')->name('version.show');
    Route::get('/app/{packageName}/version/{versionName}/edit', 'Admin\AdminAppVersionController@edit')->name('version.edit');


    Route::get('/app/{packageName}/report/{id}', 'Admin\AdminReportController@show')->name('report.show');
    Route::post('/reports/datatables', 'Admin\AdminReportController@getDataTables')->name('report.datatables');
    Route::delete('/app/{packageName}/report/{id}', 'Admin\AdminReportController@destroy')->name('report.destroy');
    Route::get('/report/{reportId}', 'Admin\AdminReportController@showFull')->name('report.show.full');

    Route::post('user/datatables', 'Admin\UserController@getDataTables')->name('user.datatables');
    Route::get('/users', 'Admin\UserController@index')->name('user.index');
    Route::get('/user', 'Admin\UserController@create')->name('user.create');
    Route::post('/user', 'Admin\UserController@store')->name('user.store');

    Route::post('admin/datatables', 'Admin\AdminController@getDataTables')->name('admin.datatables');
    Route::get('/admins', 'Admin\AdminController@index')->name('admin.index');
    Route::get('/admin', 'Admin\AdminController@create')->name('admin.create');
    Route::post('/admin', 'Admin\AdminController@store')->name('admin.store');

    Route::get('/client', 'Admin\AdminAppController@show')->name('client.show');
    Route::get('/client/edit', 'Admin\AdminAppController@edit')->name('client.edit');
    Route::get('/client/version/{versionName}/edit', 'Admin\AdminAppVersionController@edit')
        ->name('client.version.edit')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::put('/client/version/{versionName}', 'Admin\AdminAppVersionController@update')
        ->name('client.version.update')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::put('/client', 'Admin\AdminAppController@update')->name('client.update');
    Route::get('/client/version', 'Admin\AdminAppVersionController@create')->name('client.version.create');
    Route::get('/client/version/{versionName}', 'Admin\AdminAppVersionController@show')
        ->name('client.version.show')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
});

Route::name('user.')->group(function () {
    /**
     * Auth endpoint
     */
    Route::get('/login', 'User\Auth\UserLoginController@showLoginForm')->name('show');
    Route::post('/login', 'User\Auth\UserLoginController@login')->name('login');
    Route::post('/logout', 'User\Auth\UserLoginController@logout')->name('logout');

    Route::get('/home', 'User\UserHomeController@index')->name('home');

    /**
     * App endpoints
     */
    Route::post('apps/datatables', 'User\UserAppController@getDataTables')
        ->name('app.datatables');
    Route::post('app/{packageName}/reports/datatables', 'User\UserReportController@getDataTables')
        ->name('app.report.datatables')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::get('/apps', 'User\UserAppController@index')
        ->name('app.index');
    Route::get('/app', 'User\UserAppController@create')
        ->name('app.create');
    Route::post('/app', 'User\UserAppController@store')
        ->name('app.store');
    Route::put('/app/{packageName}', 'User\UserAppController@update')
        ->name('app.update')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::post('/app/{packageName}/developer', 'User\UserAppController@addPermission')
        ->name('app.developer.store')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::delete('/app/{packageName}/developer/{registrationNumber}', 'User\UserAppController@removePermission')
        ->name('app.developer.destroy')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::delete('/app/{packageName}', 'User\UserAppController@destroy')
        ->name('app.destroy')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::get('/app/{packageName}', 'User\UserAppController@show')
        ->name('app.show')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::get('/app/{packageName}/edit', 'User\UserAppController@edit')
        ->name('app.edit')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');

    /**
     * App Versions
     */
    Route::get('/app/{packageName}/version', 'User\UserAppVersionController@create')
        ->name('version.create')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::post('/app/{packageName}/version', 'User\UserAppVersionController@store')
        ->name('version.store')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
    Route::put('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@update')
        ->name('version.update')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::delete('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@destroy')
        ->name('version.destroy')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::get('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@show')
        ->name('version.show')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::get('/app/{packageName}/version/{versionName}/edit', 'User\UserAppVersionController@edit')
        ->name('version.edit')
        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');

    /**
     * App Reports
     */
    Route::get('/app/{packageName}/report/{id}', 'User\UserReportController@show')->name('report.show');
    Route::delete('/app/{packageName}/report/{id}', 'User\UserReportController@destroy')->name('report.destroy');


    Route::get('/client', 'User\UserAppController@show')->name('client.show');
    Route::get('/client/edit', 'User\UserAppController@edit')->name('client.edit');
    Route::put('/client', 'User\UserAppController@update')->name('client.update');
    Route::get('/client/version', 'User\UserAppVersionController@create')->name('client.version.create');
    Route::get('/client/version/{versionName}', 'User\UserAppVersionController@show')
        ->name('client.version.show')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::put('/client/version/{versionName}', 'User\UserAppVersionController@update')
        ->name('client.version.update')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
    Route::get('/client/version/{versionName}/edit', 'User\UserAppVersionController@edit')
        ->name('client.version.edit')
        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
});