<?php

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your app. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/report/chart', 'User\UserHomeController@getReportsChart')->name('report.chart');
Route::get('/apps/chart', 'User\UserHomeController@getAppsChart')->name('apps.chart');
Route::get('/versions/chart', 'User\UserHomeController@getVersionsChart')->name('versions.chart');

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
});