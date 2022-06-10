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

Route::get('/notifications', 'UserNotificationController@index')
    ->name('notification.index');

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

Route::get('/manual', 'UserManualController@index')
    ->name('manual');

Route::get('/documentations', 'DocumentationController@index')
    ->name('documentation');


// apps
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/apps', 'UserAppController@index')
    ->name('app.index');

Route::get('/app/{packageName}', 'UserAppController@show')
    ->name('app.show');

Route::get('/app/{packageName}/edit', 'UserAppController@edit')
    ->name('app.edit');

Route::put('/app/{packageName}', 'UserAppController@update')
    ->name('app.update');

Route::delete('/app/{packageName}', 'UserAppController@destroy')
    ->name('app.destroy');


// apps permission
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('/app/{packageName}/permission', 'UserAppController@addPermission')
    ->name('app.permission.store');

Route::delete('/app/{packageName}/permission/{registrationNumber}', 'UserAppController@removePermission')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('app.permission.destroy');


// apps datatables
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('apps/datatables', 'UserAppController@getDataTables')
    ->name('app.datatables');
Route::post('app/{packageName}/reports/datatables', 'UserReportController@getDataTables')
    ->name('app.report.datatables');


// app versions
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/app/{packageName}/version', 'UserAppVersionController@create')
    ->name('version.create');

Route::post('/app/{packageName}/version', 'UserAppVersionController@store')
    ->name('version.store');

Route::get('/app/{packageName}/version/{versionName}', 'UserAppVersionController@show')
    ->name('version.show');

Route::get('/app/{packageName}/version/{versionName}/edit', 'UserAppVersionController@edit')
    ->name('version.edit');

Route::put('/app/{packageName}/version/{versionName}', 'UserAppVersionController@update')
    ->name('version.update');

Route::delete('/app/{packageName}/version/{versionName}', 'UserAppVersionController@destroy')
    ->name('version.destroy');


// app reports
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/notifications/report/{id}', 'UserReportController@showReportFromNotification')
    ->where('id', '^[0-9A-Fa-f]{8}(?:-[0-9A-Fa-f]{4}){3}-[0-9A-Fa-f]{12}$')
    ->name('notification.report.show');

Route::get('/app/{packageName}/report/{id}', 'UserReportController@show')
    ->where('id', '^[0-9]*$')
    ->name('report.show');

Route::delete('/app/{packageName}/report/{id}', 'UserReportController@destroy')
    ->where('id', '^[0-9]*$')
    ->name('report.destroy');

Route::get('/report/{reportId}', 'UserReportController@showFull')
    ->name('report.show.full');