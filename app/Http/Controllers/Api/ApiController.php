<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\App;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AppVersion;
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
            }
            else if ($key == 'versionNumber') {
                $appVersion = AppVersion::where(['version_code' => $value, 'app_id' => $appId])->first();
            }
            else if ($key == 'versionName') {
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
            }
            else if ($key == 'versionNumber') {
                $appVersion = AppVersion::where(['version_code' => $value, 'app_id' => $appId])->first();
            }
            else if ($key == 'versionName') {
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
        }
        else {
            $data = App::orderBy('updated_at', 'DESC')->get();
        }

        foreach ($data as $key => $value) {
            $data[$key]['latest'] = AppVersion::where('app_id', $value->id)
                ->orderBy('version_code', 'DESC')
                ->first();

            // $data[$key]['developers'] = Team::where('app_id', $value->id)->get(['registration_number']);
        }

        if ($data->first() == null)
            return not_found('Application data not found');
        else
            return ok($data);
    }

    public function getAppsDataTable(Request $request)
    {

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'package_name',
            3 => 'updated_at',
        ];

        $totalData = App::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $apps = App::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $apps = App::where('package_name', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = App::where('package_name', 'LIKE', "%$search%")
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
        $json_data = array("draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }


    public function getDevelopersDataTable(Request $request)
    {

        $access_level = Auth::user()->access_level;

        $columns = [
            0 => 'registration_number',
            1 => 'name',
            2 => 'email',
            3 => 'access_level',
        ];

        $totalData = User::where('access_level', '<=', $access_level)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = User::where('access_level', '<=', $access_level)->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $users = User::where('access_level', '<=', $access_level)->where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = User::where('access_level', '<=', $access_level)->where('registration_number', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($users)) {
            foreach ($users as $user) {
                $show = route('app.show', $user->id);
                $edit = route('app.edit', $user->id);
                $nestedData['registration_number'] = $user->registration_number;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['access_level'] = $user->access_level;
                if ($user->access_level <= $access_level) {
                    $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-danger'>Delete</a>
                          &emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                }
                else {
                    $nestedData['options'] = "";
                }
                $data[] = $nestedData;
            }
        }
        $json_data = array("draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        return response()->json($json_data);
    }
}
