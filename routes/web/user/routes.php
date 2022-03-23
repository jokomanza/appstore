<?php


use Illuminate\Support\Facades\Route;


// profile
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/profile', 'UserProfileController@show')
    ->name('profile.show');

Route::get('/profile/edit', 'UserProfileController@edit')
    ->name('profile.edit');

Route::put('/profile', 'UserProfileController@update')
    ->name('profile.update');

Route::delete('/profile', 'UserProfileController@destroy')
    ->name('profile.destroy');

Route::get('/profile/password/edit', 'UserProfileController@editPassword')
    ->name('profile.password.edit');

Route::put('/profile/password', 'UserProfileController@updatePassword')
    ->name('profile.password.update');


// auth
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/login', 'Auth\UserLoginController@showLoginForm')
    ->name('show');

Route::post('/login', 'Auth\UserLoginController@login')
    ->name('login');

Route::post('/logout', 'Auth\UserLoginController@logout')
    ->name('logout');

// dashboard
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/home', 'UserHomeController@index')
    ->name('home');


// apps
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/apps', 'UserAppController@index')
    ->name('app.index');

Route::get('/app/{packageName}', 'UserAppController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.show');

Route::get('/app/{packageName}/edit', 'UserAppController@edit')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.edit');

Route::put('/app/{packageName}', 'UserAppController@update')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.update');

Route::delete('/app/{packageName}', 'UserAppController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.destroy');


// apps permission
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('/app/{packageName}/permission', 'UserAppController@addPermission')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.permission.store');

Route::delete('/app/{packageName}/permission/{registrationNumber}', 'UserAppController@removePermission')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('app.permission.destroy');


// apps datatables
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('apps/datatables', 'UserAppController@getDataTables')
    ->name('app.datatables');
Route::post('app/{packageName}/reports/datatables', 'UserReportController@getDataTables')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('app.report.datatables');


// app versions
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/app/{packageName}/version', 'UserAppVersionController@create')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.create');

Route::post('/app/{packageName}/version', 'UserAppVersionController@store')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.store');

Route::get('/app/{packageName}/version/{versionName}', 'UserAppVersionController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.show');

Route::get('/app/{packageName}/version/{versionName}/edit', 'UserAppVersionController@edit')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.edit');

Route::put('/app/{packageName}/version/{versionName}', 'UserAppVersionController@update')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.update');

Route::delete('/app/{packageName}/version/{versionName}', 'UserAppVersionController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->name('version.destroy');


// app reports
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/app/{packageName}/report/{id}', 'UserReportController@show')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('id', '^[0-9]*$')
    ->name('report.show');

Route::delete('/app/{packageName}/report/{id}', 'UserReportController@destroy')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('id', '^[0-9]*$')
    ->name('report.destroy');

Route::get('/report/{reportId}', 'UserReportController@showFull')
    ->name('report.show.full');


// client
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client', 'UserAppController@show')
    ->name('client.show');

Route::get('/client/edit', 'UserAppController@edit')
    ->name('client.edit');

Route::put('/client', 'UserAppController@update')
    ->name('client.update');


// client versions
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client/version', 'UserAppVersionController@create')
    ->name('client.version.create');

Route::post('/client/version', 'UserAppVersionController@store')
    ->name('client.version.store');

Route::get('/client/version/{versionName}', 'UserAppVersionController@show')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.show');

Route::get('/client/version/{versionName}/edit', 'UserAppVersionController@edit')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.edit');

Route::put('/client/version/{versionName}', 'UserAppVersionController@update')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.update');

Route::delete('/client/version/{versionName}', 'UserAppVersionController@delete')
    ->where('versionName', '[0-9]{1,5}.[0-9]{1,5}.[0-9]{1,5}$')
    ->name('client.version.delete');


// client reports
/*--------------------------------------------------------------------------------------------------------------------*/
Route::get('/client/report/{id}', 'UserReportController@show')
    ->where('id', '^[0-9]*$')
    ->name('client.report.show');

Route::delete('/client/report/{id}', 'UserReportController@destroy')
    ->where('id', '^[0-9]*$')
    ->name('client.report.destroy');