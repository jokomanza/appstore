<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Models\AppVersion;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $fileName
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

    /**
     * @param Request $request
     * @param $applicationId
     * @return JsonResponse
     */
    public function checkAppUpdate(Request $request, $applicationId)
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
                $applicationVersion = AppVersion::where(['id' => $value, 'application_id' => $applicationId])->first();
            } else if ($key == 'versionNumber') {
                $applicationVersion = AppVersion::where(['version_code' => $value, 'application_id' => $applicationId])->first();
            } else if ($key == 'versionName') {
                $applicationVersion = AppVersion::where(['version_name' => $value, 'application_id' => $applicationId])->first();
            }
        }

        if (!isset($applicationVersion)) {
            return not_found("App version with app id $applicationId and $requestKey $requestValue not found");
        }

        $update = AppVersion::where('version_code', '>', $applicationVersion->version_code)->orderBy('version_code', 'DESC')->first();

        if (!isset($update)) return not_found("this version has no update");

        return ok($update);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'applications' => 'required|array|min:1',
            'applications.*.package_name' => 'required|string',
            'applications.*.version_code' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        $hasUpdate = [];

        foreach ($request->all()['applications'] as $key => $value) {

            $newest = AppVersion::with("app")->where('version_code', '>', $value['version_code'])
                ->whereHas('app', function ($q) use ($value) {
                    $q->where('package_name', $value['package_name']);
                })->orderBy('version_code', 'DESC')->first();

            if (isset($newest)) $hasUpdate[] = $newest;
        }

        if (empty($hasUpdate)) return not_found("No update found");

        foreach ($hasUpdate as $key => $value) {
            $hasUpdate[$key]['updated'] = (new Carbon($value->updated_at))->diffForHumans();
        }

        return ok($hasUpdate);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getAppsDetails(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'applications' => 'required|array|min:1',
            'applications.*.package_name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return not_found('Validation fails', $validator->messages());
        }

        $data = [];

        foreach ($request->all()['applications'] as $key => $value) {

            $application = App::where('package_name', $value['package_name'])
                ->orderBy('updated_at', 'DESC')->first();

            if (isset($application)) $data[] = $application;
        }

        if (empty($data)) return ok("no applicationlications found in database");

        return ok($data);
    }

    /**
     * @param Request $request
     * @param $applicationId
     * @return JsonResponse
     */
    public function getUpdate(Request $request, $applicationId)
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
                $applicationVersion = AppVersion::where(['id' => $value, 'application_id' => $applicationId])->first();
            } else if ($key == 'versionNumber') {
                $applicationVersion = AppVersion::where(['version_code' => $value, 'application_id' => $applicationId])->first();
            } else if ($key == 'versionName') {
                $applicationVersion = AppVersion::where(['version_name' => $value, 'application_id' => $applicationId])->first();
            }
        }

        if (!isset($applicationVersion)) {
            return not_found("App version with app id $applicationId and $requestKey $requestValue not found");
        }

        $newest = AppVersion::where('version_code', '>', $applicationVersion->version_code)->where('application_id', $applicationId)->orderBy('version_code', 'DESC')->first();

        if ($newest == null) return not_found();

        return ok($newest);
    }

    /**
     * @param Request $request
     * @param $packageName
     * @param $versionCode
     * @return JsonResponse
     */
    public function checkUpdate(Request $request, $packageName, $versionCode)
    {

        $applicationVersion = AppVersion::with(['app' => function ($query) use ($packageName) {
            $query->where('package_name', '=', $packageName);
        }])->where(['version_code' => $versionCode])->first();

        if (!isset($applicationVersion)) {
            return not_found("App version with app $packageName and version code $versionCode not found");
        }

        $newest = AppVersion::where('version_code', '>', $applicationVersion->version_code)->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->orderBy('version_code', 'DESC')->first();

        if ($newest == null) {
            return not_found("This version is the latest");
        }

        return ok($newest);
    }

    /**
     * @param $applicationId
     * @return JsonResponse
     */
    public function getAppVersions($applicationId)
    {
        $data = AppVersion::where('application_id', $applicationId)
            ->orderBy('updated_at', 'DESC')
            ->get();

        if ($data->isEmpty())
            return not_found("Version for app with id $applicationId not found");

        return ok($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
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
            $data[$key]['latest'] = AppVersion::where('application_id', $value->id)
                ->orderBy('version_code', 'DESC')
                ->first();

            $data[$key]['developers'] = Permission::with('user')->where('application_id', $value->id)->get()
                ->map(function ($value) {
                    return $value->user;
                });
        }

        if ($data->first() == null)
            return not_found('Application data not found');
        else
            return ok($data);
    }

    /**
     * @param $packageName
     * @return JsonResponse
     */
    public function getLatestApp($packageName)
    {
        // DB::enableQueryLog();

        $data = App::where('package_name', $packageName)->first();

        if (!isset($data)) {
            return not_found();
        }

        $data['latest'] = AppVersion::where('application_id', $data->id)
            ->orderBy('version_code', 'DESC')
            ->first();

        $data['developers'] = Permission::with('user')->where('application_id', $data->id)->get()
            ->map(function ($value) {
                return $value->user;
            });

        return ok($data);
    }

    /**
     * Download the latest version of client app
     *
     * @param Request $request
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    public function downloadClient(Request $request)
    {

        $application = App::where('package_name', 'com.quick.quickapplicationstore')->first();

        if (!isset($application)) return not_found('Client applicationlication not found, contact developer.');

        $latest = AppVersion::where('application_id', $application->id)->orderBy('version_code', 'DESC')->first();

        if (!isset($latest)) return not_found("Client applicationlication doesn't have latest version");

        return redirect(asset('storage/' . $latest->apk_file_url));

        /*$path = asset('storage/' . $latest->apk_file_url);

        if (!File::exists($path)) return not_found('File not found ' . $path);

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", 'applicationlication\vdn.android.package-archive');
        $response->header('Content-Disposition', 'attachment; filename="' . $latest->apk_file_url . '"');

        return $response;*/
    }
}
