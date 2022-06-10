<?php


use Illuminate\Support\Facades\Route;


// home
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/settings', 'AdminSettingController@index')
    ->name('setting.index');

Route::post('/settings/manual', 'AdminSettingController@storeUserManual')
    ->name('setting.manual.store');

Route::post('/settings/development/standard', 'AdminSettingController@storeDevStandard')
    ->name('setting.development.standard.store');

Route::post('/settings/development/guide', 'AdminSettingController@storeDevGuide')
    ->name('setting.development.guide.store');

Route::post('/settings/notification/toggle', 'AdminSettingController@toggleSendMailNotification')
    ->name('setting.notification.toggle');

Route::get('/settings/cache/reset', 'AdminSettingController@clearCache')
    ->name('setting.cache.reset');


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

Route::get('/notifications', 'AdminNotificationController@index')
    ->name('notification.index');


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

Route::get('/admin/manual', 'UserManualController@index')
    ->name('manual');

Route::get('/admin/documentations', 'DocumentationController@index')
    ->name('documentation');


// apps
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/apps', 'AdminAppController@index')
    ->name('app.index');

Route::get('/app', 'AdminAppController@create')
    ->name('app.create');

Route::post('/app', 'AdminAppController@store')
    ->name('app.store');

Route::get('/app/{packageName}', 'AdminAppController@show')
    ->name('app.show');

Route::get('/app/{packageName}/edit', 'AdminAppController@edit')
    ->name('app.edit');

Route::put('/app/{packageName}', 'AdminAppController@update')
    ->name('app.update');

Route::delete('/app/{packageName}', 'AdminAppController@destroy')
    ->name('app.destroy');


// apps permission
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('/app/{packageName}/permission', 'AdminAppController@addPermission')
    ->name('app.permission.store');

Route::delete('/app/{packageName}/permission/{registrationNumber}', 'AdminAppController@removePermission')
    ->where('registrationNumber', '[A-Z]{1}[0-9]{4}$')
    ->name('app.permission.destroy');


// apps datatables
/*----------------------------------------------------------------------------------------------------------------*/
Route::post('apps/datatables', 'AdminAppController@getDataTables')
    ->name('app.datatables');
Route::post('app/{packageName}/reports/datatables', 'AdminReportController@getDataTables')
    ->name('app.report.datatables');


// app versions
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/app/{packageName}/version', 'AdminAppVersionController@create')
    ->name('version.create');

Route::post('/app/{packageName}/version', 'AdminAppVersionController@store')
    ->name('version.store');

Route::get('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@show')
    ->name('version.show');

Route::get('/app/{packageName}/version/{versionName}/edit', 'AdminAppVersionController@edit')
    ->name('version.edit');

Route::put('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@update')
    ->name('version.update');

Route::delete('/app/{packageName}/version/{versionName}', 'AdminAppVersionController@destroy')
    ->name('version.destroy');


// app reports
/*----------------------------------------------------------------------------------------------------------------*/
Route::get('/notifications/report/{id}', 'AdminReportController@showReportFromNotification')
    ->where('id', '^[0-9A-Fa-f]{8}(?:-[0-9A-Fa-f]{4}){3}-[0-9A-Fa-f]{12}$')
    ->name('notification.report.show');

Route::get('/app/{packageName}/report/{id}', 'AdminReportController@show')
    ->where('id', '^[0-9]*$')
    ->name('report.show');

Route::delete('/app/{packageName}/report/{id}', 'AdminReportController@destroy')
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