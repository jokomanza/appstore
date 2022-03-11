<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

/*
|--------------------------------------------------------------------------
| Home endpoints
|--------------------------------------------------------------------------
|
| 
*/

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/docs', 'HomeController@index')->name('docs');

Route::get('/user', 'HomeController@index')->name('user.index');

/*
|--------------------------------------------------------------------------
| Apps endpoints
|--------------------------------------------------------------------------
|
| 
*/

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

Route::get('/app/{id}/version', 'AppVersionController@create')->name('version.create');

Route::post('/app/{id}/version', 'AppVersionController@store')->name('version.store');

Route::put('/app/{id}/version/{idVersion}', 'AppVersionController@update')->name('version.update');

Route::delete('/app/{id}/version/{idVersion}', 'AppVersionController@destroy')->name('version.destroy');

Route::get('/app/{id}/version/{idVersion}', 'AppVersionController@show')->name('version.show');

Route::get('/app/{id}/version/{idVersion}/edit', 'AppVersionController@edit')->name('version.edit');


/*
|--------------------------------------------------------------------------
| Developers endpoints
|--------------------------------------------------------------------------
|
| 
*/

Route::post('developers/datatables', 'Api\ApiController@getDevelopersDataTable')->name('developer.datatables');

Route::get('/developers', 'DeveloperController@index')->name('developer.index');

Route::get('/developer', 'DeveloperController@create')->name('developer.create');

Route::post('/developer', 'DeveloperController@store')->name('developer.store');


