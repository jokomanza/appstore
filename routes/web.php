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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/developers', 'HomeController@index')->name('developer.index');

Route::get('/apps', 'AppController@index')->name('app.index');

Route::get('/app', 'AppController@create')->name('app.create');

Route::post('/app', 'AppController@store')->name('app.store');

Route::put('/app/{id}', 'AppController@update')->name('app.update');

Route::delete('/app/{id}', 'AppController@destroy')->name('app.destroy');

Route::get('/app/{id}', 'AppController@show')->name('app.show');

Route::get('/app/{id}/edit', 'AppController@edit')->name('app.edit');

