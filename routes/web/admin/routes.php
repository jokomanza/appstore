<?php


use Illuminate\Support\Facades\Route;


// home
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/settings', 'AdminSettingController@index')
    ->name('setting.index');


// profile
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/profile', 'AdminProfileController@show')
    ->name('profile.show');

Route::get('/profile/edit', 'AdminProfileController@edit')
    ->name('profile.edit');

Route::put('/profile', 'AdminProfileController@update')
    ->name('profile.update');

Route::delete('/profile', 'AdminProfileController@destroy')
    ->name('profile.destroy');

Route::get('/profile/password/edit', 'AdminProfileController@editPassword')
    ->name('profile.password.edit');

Route::put('/profile/password', 'AdminProfileController@updatePassword')
    ->name('profile.password.update');


// auth
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/login', 'Auth\AdminLoginController@showLoginForm')
    ->name('show');

Route::post('/login', 'Auth\AdminLoginController@login')
    ->name('login');

Route::post('/logout', 'Auth\AdminLoginController@logout')
    ->name('logout');


// dashboard
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/', function () {
    return redirect()->route('admin.home');
});

Route::get('/home', 'AdminHomeController@index')
    ->name('home');


// apps
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/apps', 'AdminAppController@index')
    ->name('app.index');

Route::get('/app', 'AdminAppController@create')
    ->name('app.create');

Route::post('/app', 'AdminAppController@store')
    ->name('app.store');

Route::get('/app/{packageName}', 'AdminAppController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.show');

Route::get('/app/{packageName}/edit', 'AdminAppController@edit')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.edit');

Route::put('/app/{packageName}', 'AdminAppController@update')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.update');

Route::delete('/app/{packageName}', 'AdminAppController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.destroy');


// apps permission
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('/app/{packageName}/permission', 'AdminAppController@addPermission')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.permission.store');

Route::delete('/app/{packageName}/permission/{registrationNumber}', 'AdminAppController@removePermission')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('app.permission.destroy');


// apps datatables
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('apps/datatables', 'AdminAppController@getDataTables')
    ->name('app.datatables');
Route::post('app/{packageName}/reports/datatables', 'AdminReportController@getDataTables')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.report.datatables');


// app versions
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/app/{packageName}/version', 'AdminAppVersionController@create')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.create');

Route::post('/app/{packageName}/version', 'AdminAppVersionController@store')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.store');

Route::get('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.show');

Route::get('/app/{packageName}/version/{versionName}/edit', 'AdminAppVersionController@edit')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.edit');

Route::put('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@update')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.update');

Route::delete('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.destroy');


// app reports
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/notifications/report/{id}', 'AdminReportController@showReportFromNotification')
    ->where('id', '^[0-9A-Fa-f]{8}(?:-[0-9A-Fa-f]{4}){3}-[0-9A-Fa-f]{12}$')
    ->name('notification.report.show');

Route::get('/app/{packageName}/report/{id}', 'AdminReportController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('id', '^[0-9]*$')
    ->name('report.show');

Route::delete('/app/{packageName}/report/{id}', 'AdminReportController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('id', '^[0-9]*$')
    ->name('report.destroy');

Route::get('/report/{reportId}', 'AdminReportController@showFull')
    ->name('report.show.full');


// user
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/users', 'UserController@index')
    ->name('user.index');

Route::get('/user', 'UserController@create')
    ->name('user.create');

Route::post('/user', 'UserController@store')
    ->name('user.store');

Route::get('/user/{registrationNumber}', 'UserController@show')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('user.show');

Route::get('/user/{registrationNumber}/edit', 'UserController@edit')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('user.edit');

Route::put('/user/{registrationNumber}', 'UserController@update')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('user.update');

Route::delete('/user/{registrationNumber}', 'UserController@destroy')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('user.destroy');

Route::put('/user/{registrationNumber}/password/reset', 'UserController@resetPassword')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('user.password.reset');

Route::post('user/datatables', 'UserController@getDataTables')
    ->name('user.datatables');


// admin
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('admin/datatables', 'AdminController@getDataTables')
    ->name('admin.datatables');

Route::get('/admins', 'AdminController@index')
    ->name('admin.index');

Route::get('/admin', 'AdminController@create')
    ->name('admin.create');

Route::post('/admin', 'AdminController@store')
    ->name('admin.store');


// client
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client', 'AdminAppController@show')
    ->name('client.show');

Route::get('/client/edit', 'AdminAppController@edit')
    ->name('client.edit');

Route::put('/client', 'AdminAppController@update')
    ->name('client.update');


// client versions
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client/version', 'AdminAppVersionController@create')
    ->name('client.version.create');

Route::post('/client/version', 'AdminAppVersionController@store')
    ->name('client.version.store');

Route::get('/client/version/{versionName}', 'AdminAppVersionController@showCLient')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.show');

Route::get('/client/version/{versionName}/edit', 'AdminAppVersionController@editClient')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.edit');

Route::put('/client/version/{versionName}', 'AdminAppVersionController@updateClient')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.update');

Route::delete('/client/version/{versionName}', 'AdminAppVersionController@deleteClient')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.delete');


// client reports
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client/report/{id}', 'AdminReportController@showClient')
    ->where('id', '^[0-9]*$')
    ->name('client.report.show');

Route::delete('/client/report/{id}', 'AdminReportController@destroyClient')
    ->where('id', '^[0-9]*$')
    ->name('client.report.destroy');