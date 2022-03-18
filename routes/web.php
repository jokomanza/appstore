<?php

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::group(['prefix' => 'admin'], function () {
    Route::name('admin.')->group(function () {
            Route::get('/login', 'Auth\Admin\LoginController@showLoginForm')->name('show');
            Route::post('/login', 'Auth\Admin\LoginController@login')->name('login');
            Route::get('/home', 'Admin\HomeController@index')->name('home');

            Route::post('apps/datatables', 'Admin\AppController@getDataTables')->name('app.datatables');
            Route::post('app/{id}/reports/datatables', 'Admin\AppController@getReportsDataTables')->name('app.report.datatables');
            Route::get('/apps', 'Admin\AppController@index')->name('app.index');
            Route::get('/app', 'Admin\AppController@create')->name('app.create');
            Route::post('/app', 'Admin\AppController@store')->name('app.store');
            Route::put('/app/{id}', 'Admin\AppController@update')->name('app.update');
            Route::post('/app/{id}/developer', 'Admin\AppController@addDeveloper')->name('app.developer.store');
            Route::delete('/app/{id}/developer/{registrationNumber}', 'Admin\AppController@removeDeveloper')->name('app.developer.destroy');
            Route::delete('/app/{id}', 'Admin\AppController@destroy')->name('app.destroy');
            Route::get('/app/{id}', 'Admin\AppController@show')->name('app.show');
            Route::get('/app/{id}/edit', 'Admin\AppController@edit')->name('app.edit');
            Route::post('/app/{id}/reports/datatables', 'Admin\AppController@getReportsDataTables')->name('app.report.datatables');

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
});



/* |-------------------------------------------------------------------------- | Home endpoints |-------------------------------------------------------------------------- | |  */

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/docs', 'HomeController@index')->name('docs');

Route::get('/user', 'HomeController@index')->name('user.index');

/* |-------------------------------------------------------------------------- | Apps endpoints |-------------------------------------------------------------------------- | |  */

Route::post('apps/datatables', 'Api\ApiController@getAppsDataTable')->name('app.datatables');

Route::get('/apps', 'AppController@index')->name('app.index');

Route::get('/app', 'AppController@create')->name('app.create');

Route::post('/app', 'AppController@store')->name('app.store');

Route::put('/app/{id}', 'AppController@update')->name('app.update');

Route::post('/app/{id}/developer', 'AppController@addDeveloper')->name('app.developer.store');

Route::delete('/app/{id}/developer/{registrationNumber}', 'AppController@removeDeveloper')->name('app.developer.destroy');

Route::delete('/app/{id}', 'AppController@destroy')->name('app.destroy');

Route::get('/app/{id}', 'AppController@show')->name('app.show');

Route::get('/app/{id}/edit', 'AppController@edit')->name('app.edit');

Route::post('/app/{id}/reports/datatables', 'AppController@getReportsDataTables')->name('app.report.datatables');

Route::get('/app/{id}/version', 'AppVersionController@create')->name('version.create');

Route::post('/app/{id}/version', 'AppVersionController@store')->name('version.store');

Route::put('/app/{id}/version/{idVersion}', 'AppVersionController@update')->name('version.update');

Route::delete('/app/{id}/version/{idVersion}', 'AppVersionController@destroy')->name('version.destroy');

Route::get('/app/{id}/version/{idVersion}', 'AppVersionController@show')->name('version.show');

Route::get('/app/{id}/version/{idVersion}/edit', 'AppVersionController@edit')->name('version.edit');


/* |-------------------------------------------------------------------------- | Reports endpoints |-------------------------------------------------------------------------- | |  */
Route::get('/reports', 'ReportController@index')->name('report.index');

Route::get('/app/{packageName}/report/{id}', 'ReportController@show')->name('report.show');

Route::post('/reports/datatables', 'ReportController@getDataTables')->name('report.datatables');

Route::delete('/app/{packageName}/report/{id}', 'ReportController@destroy')->name('report.destroy');

Route::get('/report/{reportId}', 'ReportController@showFull')->name('report.show.full');


/* |-------------------------------------------------------------------------- | Developers endpoints |-------------------------------------------------------------------------- | |  */



/* |-------------------------------------------------------------------------- | Developers endpoints |-------------------------------------------------------------------------- | |  */


