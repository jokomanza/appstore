<?php

use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | is assigned the "api" middleware group. Enjoy building your API! | */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/documentation', function() {
    return "Under development";
})->name('api.docs');

Route::get('/client/download', 'Api\ApiController@downloadClient')->name('api.client.download');

Route::get('apps', 'Api\ApiController@getAllApps');

Route::get('app/{packageName}/latest', 'Api\ApiController@getLatestApp');

Route::get('/download/{fileName}', 'Api\ApiController@download');

Route::get("/app/{appId}/versions", 'Api\ApiController@getAppVersions');

Route::get("/app/{appId}/version/{versionId}/update", 'Api\ApiController@checkAppUpdate');

Route::get("/app/{packageName}/version/{versionCode}/library/update", 'Api\ApiController@checkUpdate')
    ->where('packageName', 'com.quick.[a-z0-9]{3,30}$')
    ->where('versionCode', '^[0-9]*$');

Route::get("/app/{appId}/version/update", 'Api\ApiController@getUpdate');

Route::post("/versions/update", 'Api\ApiController@getAllUpdate');

Route::post("/apps/details", 'Api\ApiController@getAppsDetails');

Route::get('/client/reports', 'Api\ReportController@index');

Route::post('/app/report', 'Api\ReportController@store');

Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return ok(['message' => 'Cleared!']);
});