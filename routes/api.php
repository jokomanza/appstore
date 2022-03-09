<?php

use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('apps', 'Api\ApiController@getAllApps')->name('api.app.index');

/**
 * Download apk file 
 */
Route::get('/download/{fileName}', 'Api\ApiController@download');

Route::get("/app/{appId}/versions", 'Api\ApiController@getAppVersions');

Route::get("/app/{appId}/version/{versionId}/update", 'Api\ApiController@checkAppUpdate');

Route::get("/app/{appId}/version/update", 'Api\ApiController@getUpdate');