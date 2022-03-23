<?php

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/docs', 'User\UserDocumentationController@index')->name('user.docs');
Route::get('/admin/docs', 'Admin\AdminDocumentationController@index')->name('admin.docs');

//Auth::routes();

Route::get('password/reset', 'User\Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'User\Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'User\Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'User\Auth\ResetPasswordController@reset');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin'], function () {

    include 'web/admin/routes.php';
});

Route::group([
    'as' => 'user.',
    'namespace' => 'User'], function () {


    include 'web/user/routes.php';


//    /**
//     * Auth endpoint
//     */
//    Route::get('/login', 'User\Auth\UserLoginController@showLoginForm')->name('show');
//    Route::post('/login', 'User\Auth\UserLoginController@login')->name('login');
//    Route::post('/logout', 'User\Auth\UserLoginController@logout')->name('logout');
//
//    Route::get('/home', 'User\UserHomeController@index')->name('home');
//
//    /**
//     * App endpoints
//     */
//    Route::post('apps/datatables', 'User\UserAppController@getDataTables')
//        ->name('app.datatables');
//    Route::post('app/{packageName}/reports/datatables', 'User\UserReportController@getDataTables')
//        ->name('app.report.datatables')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::get('/apps', 'User\UserAppController@index')
//        ->name('app.index');
//    Route::get('/app', 'User\UserAppController@create')
//        ->name('app.create');
//    Route::post('/app', 'User\UserAppController@store')
//        ->name('app.store');
//    Route::put('/app/{packageName}', 'User\UserAppController@update')
//        ->name('app.update')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::post('/app/{packageName}/developer', 'User\UserAppController@addPermission')
//        ->name('app.developer.store')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::delete('/app/{packageName}/developer/{registrationNumber}', 'User\UserAppController@removePermission')
//        ->name('app.developer.destroy')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::delete('/app/{packageName}', 'User\UserAppController@destroy')
//        ->name('app.destroy')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::get('/app/{packageName}', 'User\UserAppController@show')
//        ->name('app.show')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::get('/app/{packageName}/edit', 'User\UserAppController@edit')
//        ->name('app.edit')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//
//    /**
//     * App Versions
//     */
//    Route::get('/app/{packageName}/version', 'User\UserAppVersionController@create')
//        ->name('version.create')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::post('/app/{packageName}/version', 'User\UserAppVersionController@store')
//        ->name('version.store')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$');
//    Route::put('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@update')
//        ->name('version.update')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//    Route::delete('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@destroy')
//        ->name('version.destroy')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//    Route::get('/app/{packageName}/version/{versionName}', 'User\UserAppVersionController@show')
//        ->name('version.show')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//    Route::get('/app/{packageName}/version/{versionName}/edit', 'User\UserAppVersionController@edit')
//        ->name('version.edit')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//
//    /**
//     * App Reports
//     */
//    Route::get('/app/{packageName}/report/{id}', 'User\UserReportController@show')
//        ->name('report.show')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('id', '[0-9]$');
//    Route::delete('/app/{packageName}/report/{id}', 'User\UserReportController@destroy')
//        ->name('report.destroy')
//        ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
//        ->where('id', '[0-9]$');
//    Route::get('/report/{reportId}', 'User\UserReportController@showFull')->name('report.show.full');
//
//
//    Route::get('/client', 'User\UserAppController@show')->name('client.show');
//    Route::get('/client/edit', 'User\UserAppController@edit')->name('client.edit');
//    Route::put('/client', 'User\UserAppController@update')->name('client.update');
//    Route::get('/client/version', 'User\UserAppVersionController@create')->name('client.version.create');
//    Route::get('/client/version/{versionName}', 'User\UserAppVersionController@show')
//        ->name('client.version.show')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//    Route::put('/client/version/{versionName}', 'User\UserAppVersionController@update')
//        ->name('client.version.update')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//    Route::get('/client/version/{versionName}/edit', 'User\UserAppVersionController@edit')
//        ->name('client.version.edit')
//        ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$');
//
//    Route::get('/client/report/{id}', 'User\UserReportController@show')->name('client.report.show')
//        ->where('id', '^[0-9]*$');
//    Route::delete('/client/report/{id}', 'User\UserReportController@destroy')->name('client.report.destroy')
//        ->where('id', '^[0-9]*$');
});