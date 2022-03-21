<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\AppVersion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $appId
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $fileName)
    {

        $path = public_path('storage/' . $fileName);

        if (!File::exists($path)) {
            return not_found('File not found');
        }

        $file = File::get($path);
        $type = File::mimeType($path);
        $size = filesize($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        $response->header("Content-Length", $size);

        return $response;
    }

    public function checkAppUpdate(Request $request, $appId)
    {

        $validator = Validator::make($request->all(), [
            'versionId' => 'required_without_all:versionName,versionNumber|numeric',
            'versionNumber' => 'required_without_all:versionId,versionName|numeric',
            'versionName' => 'required_without_all:versionId,versionNumber|string'
        ]);

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        foreach ($request->all() as $key => $value) {
            $requestKey = $key;
            $requestValue = $value;
            if ($key == 'versionId') {
                $appVersion = AppVersion::where(['id' => $value, 'app_id' => $appId])->first();
            } else if ($key == 'versionNumber') {
                $appVersion = AppVersion::where(['version_code' => $value, 'app_id' => $appId])->first();
            } else if ($key == 'versionName') {
                $appVersion = AppVersion::where(['version_name' => $value, 'app_id' => $appId])->first();
            }
        }

        if (!isset($appVersion)) {
            return not_found("App version with app id $appId and $requestKey $requestValue not found");
        }

        $update = AppVersion::where('version_code', '>', $appVersion->version_code)->orderBy('version_code', 'DESC')->first();

        if (!isset($update)) {
            return not_found("this version has no update");
        }

        return ok($update);
    }

    public function getAllUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'apps' => 'required|array|min:1',
            'apps.*.package_name' => 'required|string',
            'apps.*.version_code' => 'required|numeric'
        ]);

        // dump($validator->messages());
        // die;

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        $hasUpdate = [];

        foreach ($request->all()['apps'] as $key => $value) {

            // dump($request->all());


            $newest = AppVersion::with("app")->where('version_code', '>', $value['version_code'])
                ->whereHas('app', function ($q) use ($value) {
                    $q->where('package_name', $value['package_name']);
                })->orderBy('version_code', 'DESC')->first();

            if (isset($newest)) {
                $hasUpdate[] = $newest;
            }
        }

        if (empty($hasUpdate)) {
            return not_found("No update found");
        }

        foreach ($hasUpdate as $key => $value) {
            $hasUpdate[$key]['updated'] = (new Carbon($value->updated_at))->diffForHumans();
        }

        return ok($hasUpdate);
    }

    public function getAppsDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'apps' => 'required|array|min:1',
            'apps.*.package_name' => 'required|string'
        ]);

        // dump($validator->messages());
        // die;

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        $data = [];

        foreach ($request->all()['apps'] as $key => $value) {

            // dump($request->all());

            $app = App::where('package_name', $value['package_name'])
                ->orderBy('updated_at', 'DESC')->first();

            if (isset($app)) {
                $data[] = $app;
            }
        }

        if (empty($data)) {
            return ok("no applications found in database");
        }

        return ok($data);
    }

    public function getUpdate(Request $request, $appId)
    {

        $validator = Validator::make($request->all(), [
            'versionId' => 'required_without_all:versionName,versionNumber|numeric',
            'versionNumber' => 'required_without_all:versionId,versionName|numeric',
            'versionName' => 'required_without_all:versionId,versionNumber|string'
        ]);

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        foreach ($request->all() as $key => $value) {
            $requestKey = $key;
            $requestValue = $value;
            if ($key == 'versionId') {
                $appVersion = AppVersion::where(['id' => $value, 'app_id' => $appId])->first();
            } else if ($key == 'versionNumber') {
                $appVersion = AppVersion::where(['version_code' => $value, 'app_id' => $appId])->first();
            } else if ($key == 'versionName') {
                $appVersion = AppVersion::where(['version_name' => $value, 'app_id' => $appId])->first();
            }
        }

        if (!isset($appVersion)) {
            return not_found("App version with app id $appId and $requestKey $requestValue not found");
        }

        $newest = AppVersion::where('version_code', '>', $appVersion->version_code)->where('app_id', $appId)->orderBy('version_code', 'DESC')->first();

        if ($newest == null) {
            return not_found();
        }

        return ok($newest);
    }

    public function checkUpdate(Request $request, $packageName, $versionCode)
    {

        $appVersion = AppVersion::with(['app' => function ($query) use ($packageName) {
            $query->where('package_name', '=', $packageName);
        }])->where(['version_code' => $versionCode])->first();

        if (!isset($appVersion)) {
            return not_found("App version with app $packageName and version code $versionCode not found");
        }

        $newest = AppVersion::where('version_code', '>', $appVersion->version_code)->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->orderBy('version_code', 'DESC')->first();

        if ($newest == null) {
            return not_found();
        }

        return ok($newest);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAppVersions($appId)
    {
        $data = AppVersion::where('app_id', $appId)
            ->orderBy('updated_at', 'DESC')
            ->get();

        if ($data->isEmpty())
            return not_found("Version for app with id $appId not found");

        return ok($data);
    }

    public function getAllApps(Request $request)
    {
        // DB::enableQueryLog();

        if ($request->has('s') && trim($request->s) !== '') {
            $data = App::where('name', 'like', "%$request->s%")
                ->orderBy('updated_at', 'DESC')->get();
        } else {
            $data = App::orderBy('updated_at', 'DESC')->get();
        }

        foreach ($data as $key => $value) {
            $data[$key]['latest'] = AppVersion::where('app_id', $value->id)
                ->orderBy('version_code', 'DESC')
                ->first();

            $data[$key]['developers'] = Developer::with('user')->where('app_id', $value->id)->get()
                ->map(function ($value) {
                    return $value->user;
                });
        }

        if ($data->first() == null)
            return not_found('Application data not found');
        else
            return ok($data);
    }

    public function getLatestApp($packageName)
    {
        // DB::enableQueryLog();

        $data = App::where('package_name', $packageName)->first();

        if (!isset($data)) {
            return not_found();
        }

        $data['latest'] = AppVersion::where('app_id', $data->id)
            ->orderBy('version_code', 'DESC')
            ->first();

        $data['developers'] = Developer::with('user')->where('app_id', $data->id)->get()
            ->map(function ($value) {
                return $value->user;
            });

        return ok($data);
    }

    public function downloadClient(Request $request)
    {

        $app = App::where('package_name', 'com.quick.quickappstore')->first();

        if (!isset($app))
            return not_found('Application not found');

        $latest = AppVersion::where('app_id', $app->id)->orderBy('version_code', 'DESC')->first();

        if (!isset($latest))
            return not_found('Latest version not found');

        $path = public_path('storage/' . $latest->apk_file_url);

        if (!File::exists($path)) {
            return not_found('File not found');
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", 'application\vdn.android.package-archive');
        $response->header('Content-Disposition', 'attachment; filename="' . $latest->apk_file_url . '"');

        return $response;
    }

    public function getAppsDataTable(Request $request)
    {

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'package_name',
            3 => 'updated_at',
        ];

        $totalData = App::where('package_name', '!=', 'com.quick.quickappstore')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $apps = App::offset($start)
                ->where('package_name', '!=', 'com.quick.quickappstore')
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $apps = App::where('package_name', 'LIKE', "%$search%")
                ->where('package_name', '!=', 'com.quick.quickappstore')
                ->orWhere('name', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = App::where('package_name', 'LIKE', "%$search%")
                ->where('package_name', '!=', 'com.quick.quickappstore')
                ->orWhere('name', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($apps)) {
            foreach ($apps as $app) {
                $show = route('app.show', $app->id);
                $edit = route('app.edit', $app->id);
                $nestedData['id'] = $app->id;
                $nestedData['name'] = $app->name;
                $nestedData['package_name'] = $app->package_name;
                $nestedData['updated_at'] = (new Carbon($app->updated_at))->diffForHumans();
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>
                      &emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }
}
