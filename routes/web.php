<?php

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use App\Http\Controllers\Admin\AdminAppController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Auth\UserLoginController;
use App\Http\Controllers\Admin\UserHomeController;
use App\Http\Controllers\Admin\UserAppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Route::name('admin.')->group(function () {
        Route::get('/login', 'Admin\Auth\AdminLoginController@showLoginForm')->name('show');
        Route::post('/login', 'Admin\Auth\AdminLoginController@login')->name('login');
        Route::post('/logout', 'Admin\Auth\AdminLoginController@logout')->name('logout');

        Route::get('/home', 'Admin\AdminHomeController@index')->name('home');

        Route::post('apps/datatables', 'Admin\AdminAppController@getDataTables')->name('app.datatables');
        Route::post('app/{packageName}/reports/datatables', 'Admin\AdminAppController@getReportsDataTables')->name('app.report.datatables');
        Route::get('/apps', 'Admin\AdminAppController@index')->name('app.index');
        Route::get('/app', 'Admin\AdminAppController@create')->name('app.create');
        Route::post('/app', 'Admin\AdminAppController@store')->name('app.store');
        Route::put('/app/{packageName}', 'Admin\AdminAppController@update')->name('app.update');
        Route::post('/app/{packageName}/developer', 'Admin\AdminAppController@addPermission')->name('app.developer.store');
        Route::delete('/app/{packageName}/developer/{registrationNumber}', 'Admin\AdminAppController@removePermission')->name('app.developer.destroy');
        Route::delete('/app/{packageName}', 'Admin\AdminAppController@destroy')->name('app.destroy');
        Route::get('/app/{id}', 'Admin\AdminAppController@show')->name('app.show');
        Route::get('/app/{id}/edit', 'Admin\AdminAppController@edit')->name('app.edit');

        Route::get('/app/{packageName}/version', 'Admin\AdminAppVersionController@create')->name('version.create');
        Route::post('/app/{packageName}/version', 'Admin\AdminAppVersionController@store')->name('version.store');
        Route::put('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@update')->name('version.update');
        Route::delete('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@destroy')->name('version.destroy');
        Route::get('/app/{packageName}/version/{versionName}', 'Admin\AdminAppVersionController@show')->name('version.show');
        Route::get('/app/{packageName}/version/{versionName}/edit', 'Admin\AdminAppVersionController@edit')->name('version.edit');

        Route::get('/reports', 'ReportController@index')->name('report.index');
        Route::get('/app/{packageName}/report/{id}', 'ReportController@show')->name('report.show');
        Route::post('/reports/datatables', 'ReportController@getDataTables')->name('report.datatables');
        Route::delete('/app/{packageName}/report/{id}', 'ReportController@destroy')->name('report.destroy');
        Route::get('/report/{reportId}', 'ReportController@showFull')->name('report.show.full');

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
        Route::put('/client', 'Admin\AdminAppController@update')->name('client.update');

        Route::get('/client/version', 'Admin\AppVersionController@create')->name('client.version.create');
    });
});

/**
 * User Endpoints
 */

Route::name('user.')->group(function () {
    Route::get('/login', 'User\Auth\UserLoginController@showLoginForm')->name('show');
    Route::post('/login', 'User\Auth\UserLoginController@login')->name('login');
    Route::post('/logout', 'User\Auth\UserLoginController@logout')->name('logout');

    Route::get('/home', 'User\UserHomeController@index')->name('home');

    Route::post('apps/datatables', 'User\UserAppController@getDataTables')->name('app.datatables');
    Route::post('app/{id}/reports/datatables', 'User\UserAppController@getReportsDataTables')->name('app.report.datatables');
    Route::get('/apps', 'User\UserAppController@index')->name('app.index');
    Route::get('/app', 'User\UserAppController@create')->name('app.create');
    Route::post('/app', 'User\UserAppController@store')->name('app.store');
    Route::put('/app/{id}', 'User\UserAppController@update')->name('app.update');
    Route::post('/app/{id}/developer', 'User\UserAppController@addPermission')->name('app.developer.store');
    Route::delete('/app/{id}/developer/{registrationNumber}', 'User\UserAppController@removePermission')->name('app.developer.destroy');
    Route::delete('/app/{id}', 'User\UserAppController@destroy')->name('app.destroy');
    Route::get('/app/{id}', 'User\UserAppController@show')->name('app.show');
    Route::get('/app/{id}/edit', 'User\UserAppController@edit')->name('app.edit');

    Route::get('/app/{id}/version', 'Admin\AppVersionController@create')->name('version.create');
    Route::post('/app/{id}/version', 'Admin\AppVersionController@store')->name('version.store');
    Route::put('/app/{id}/version/{idVersion}', 'Admin\AppVersionController@update')->name('version.update');
    Route::delete('/app/{id}/version/{idVersion}', 'Admin\AppVersionController@destroy')->name('version.destroy');
    Route::get('/app/{id}/version/{idVersion}', 'Admin\AppVersionController@show')->name('version.show');
    Route::get('/app/{id}/version/{idVersion}/edit', 'Admin\AppVersionController@edit')->name('version.edit');

    Route::get('/reports', 'ReportController@index')->name('report.index');
    Route::get('/app/{packageName}/report/{id}', 'ReportController@show')->name('report.show');
    Route::post('/reports/datatables', 'ReportController@getDataTables')->name('report.datatables');
    Route::delete('/app/{packageName}/report/{id}', 'ReportController@destroy')->name('report.destroy');
    Route::get('/report/{reportId}', 'ReportController@showFull')->name('report.show.full');

    Route::post('user/datatables', 'UserController@getDataTables')->name('user.datatables');
    Route::get('/users', 'UserController@index')->name('user.index');
    Route::get('/user', 'UserController@create')->name('user.create');
    Route::post('/user', 'UserController@store')->name('user.store');

    Route::get('/client', 'Admin\AppController@show')->name('client.show');
    Route::get('/client/edit', 'Admin\AppController@edit')->name('client.edit');
    Route::put('/client', 'Admin\AppController@update')->name('client.update');

    Route::get('/client/version', 'Admin\AppVersionController@create')->name('client.version.create');
});
///*
///* |-------------------------------------------------------------------------- | Home endpoints |-------------------------------------------------------------------------- | |  */
//
//Route::get('/home', 'UserHomeController@index')->name('home');
//
Route::get('/docs', function () {

})->name('docs');
Route::get('/docs', function () {

})->name('docs');
//
//Route::get('/user', 'UserHomeController@index')->name('user.index');
//
///* |-------------------------------------------------------------------------- | Apps endpoints |-------------------------------------------------------------------------- | |  */
//
//Route::post('apps/datatables', 'Api\ApiController@getAppsDataTable')->name('app.datatables');
//
//Route::get('/apps', 'AdminAppController@index')->name('app.index');
//
//Route::get('/app', 'AdminAppController@create')->name('app.create');
//
//Route::post('/app', 'AdminAppController@store')->name('app.store');
//
//Route::put('/app/{id}', 'AdminAppController@update')->name('app.update');
//
//Route::post('/app/{id}/developer', 'AdminAppController@addDeveloper')->name('app.developer.store');
//
//Route::delete('/app/{id}/developer/{registrationNumber}', 'AdminAppController@removeDeveloper')->name('app.developer.destroy');
//
//Route::delete('/app/{id}', 'AdminAppController@destroy')->name('app.destroy');
//
//Route::get('/app/{id}', 'AdminAppController@show')->name('app.show');
//
//Route::get('/app/{id}/edit', 'AdminAppController@edit')->name('app.edit');
//
//Route::post('/app/{id}/reports/datatables', 'AdminAppController@getReportsDataTables')->name('app.report.datatables');
//
//Route::get('/app/{id}/version', 'AppVersionController@create')->name('version.create');
//
//Route::post('/app/{id}/version', 'AppVersionController@store')->name('version.store');
//
//Route::put('/app/{id}/version/{idVersion}', 'AppVersionController@update')->name('version.update');
//
//Route::delete('/app/{id}/version/{idVersion}', 'AppVersionController@destroy')->name('version.destroy');
//
//Route::get('/app/{id}/version/{idVersion}', 'AppVersionController@show')->name('version.show');
//
//Route::get('/app/{id}/version/{idVersion}/edit', 'AppVersionController@edit')->name('version.edit');
//
//
///* |-------------------------------------------------------------------------- | Reports endpoints |-------------------------------------------------------------------------- | |  */
//Route::get('/reports', 'ReportController@index')->name('report.index');
//
//Route::get('/app/{packageName}/report/{id}', 'ReportController@show')->name('report.show');
//
//Route::post('/reports/datatables', 'ReportController@getDataTables')->name('report.datatables');
//
//Route::delete('/app/{packageName}/report/{id}', 'ReportController@destroy')->name('report.destroy');
//
//Route::get('/report/{reportId}', 'ReportController@showFull')->name('report.show.full');
//
//
///* |-------------------------------------------------------------------------- | Developers endpoints |-------------------------------------------------------------------------- | |  */
//
//
///* |-------------------------------------------------------------------------- | Developers endpoints |-------------------------------------------------------------------------- | |  */*/


