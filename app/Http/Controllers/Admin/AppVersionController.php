<?php

namespace App\Http\Controllers\Admin;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateAppVersionRequest;
use App\Interfaces\AppVersionServiceInterface;
use App\Http\Requests\CreateAppVersionRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use App\Http\Controllers\Base\Controller;

class AppVersionController extends Controller
{
    private $service;

    public function __construct(AppVersionServiceInterface $service)
    {
        $this->middleware('auth:admin');
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View 
     */
    public function create(Request $request)
    {
        if ($request->routeIs('admin.client.version.create')) {
            $app = App::where('package_name', 'com.quick.quickappstore')->first();
        } else {
            $id = $request->id;
            $app = App::find($id);
        }

        return view('apps.versions.admin.create')->with(['app' => $app]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse 
     */
    public function store(CreateAppVersionRequest $request, $id)
    {
        // dd($request->all());

        $app = App::find($id);

        if ($app == null) {
            return view('errors.404');
        }

        $time = time();

        $storedApk = $this->service->handleUploadedApk($app->package_name, $request->file('apk_file'), $time);
        $storedApkName = $storedApk['name'];
        $storedApkSize = $storedApk['size'];
        $storedIconName = $this->service->handleUploadedIcon($app->package_name, $request->file('icon_file'), $time);

        $apk = new \ApkParser\Parser(public_path('/storage/' . $storedApkName));

        $manifest = $apk->getManifest();
        $package_name = $manifest->getPackageName();
        $version_code = $manifest->getVersionCode();
        $version_name = $manifest->getVersionName();
        $min_sdk_level = $manifest->getMinSdkLevel();
        $target_sdk_level = $manifest->getTargetSdkLevel();

        $additionalError = [];

        // Additional validation
        if ($app->package_name != $package_name) {
            $additionalError['package_name'] = [
                "package name uploaded file (" . $package_name . ") not match with current app package name (" . $app->package_name . ")"
            ];
        }

        if (!empty(AppVersion::where(['app_id' => $id, 'version_code' => $version_code])->first())) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name already exists"
            ];
        }
        $maxVersionNumber = AppVersion::where(['app_id' => $id])->max('version_code');
        if ($maxVersionNumber >= $version_code) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name must greater than $maxVersionNumber "
            ];
        }
        if (!empty(AppVersion::where(['app_id' => $id, 'version_name' => $version_name])->first())) {
            $additionalError['version_name'] = [
                "Version name " . $version_name . " for app $app->name already exists"
            ];
        }

        if (!empty($additionalError)) {
            @unlink(public_path('/storage/') . $storedApkName);
            @unlink(public_path('/storage/') . $storedIconName);
            // dd($additionalError);

            return back()->withErrors($additionalError)->withInput();
        }

        $finalApkName = $package_name . '.' . $version_code . '.' . time() . '.apk';
        $finalIconName = $package_name . '.' . $version_code . '.icon.' . time() . '.jpg';
        File::move(public_path('/storage/') . $storedApkName, public_path('/storage/') . $finalApkName);
        File::move(public_path('/storage/') . $storedIconName, public_path('/storage/') . $finalIconName);

        $storedApkName = $finalApkName;
        $storedIconName = $finalIconName;

        $appVersion = new AppVersion();
        $appVersion->app_id = $id;
        $appVersion->version_code = $version_code;
        $appVersion->version_name = $version_name;
        $appVersion->min_sdk_level = $min_sdk_level;
        $appVersion->target_sdk_level = $target_sdk_level;
        $appVersion->apk_file_url = $storedApkName;
        $appVersion->apk_file_size = $storedApkSize;
        $appVersion->icon_url = $storedIconName;
        $appVersion->description = $request->description;
        if (!$appVersion->save()) {
            @unlink(public_path('/storage/') . $storedApkName);
            @unlink(public_path('/storage/') . $storedIconName);
            
            return back()->withErrors("failed to save app version");
        }

        return redirect()->route('admin.app.show', [$id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\View\View
     */
    public function show($id, $idVersion)
    {
        $app = App::find($id);
        $version = AppVersion::find($idVersion);
        return view('apps.versions.admin.show')->with(['app' => $app, 'version' => $version]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\View\View
     */
    public function edit($id, $versionId)
    {
        $app = App::find($id);
        $version = AppVersion::where(['id' => $versionId, 'app_id' => $id])->first();

        if (!isset($app) || !isset($version)) {
            return view('errors.404');
        }

        return view('apps.versions.admin.edit', compact('app', 'version'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update(UpdateAppVersionRequest $request, $id, $versionId)
    {
        $version = AppVersion::where(['id' => $versionId, 'app_id' => $id])->first();

        if (!isset($version)) {
            return view('errors.404');
        }

        $version->description = $request->description;

        if ($version->update()) {
            return redirect()->route('admin.version.show', [$id, $versionId]);
        }
        else {
            return back()->withErrors('Failed to update data');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  int  $versionId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, $versionId)
    {
        if (Auth::user()->access_level == 1) throw new UnauthorizedException();
        
        $version = AppVersion::where(['app_id' => $id, 'id' => $versionId])->first();

        if (!$version) {
            return back()->withErrors('Target version not found');
        }

        if ($version->delete()) {
            $this->service->handleDeletedVersion($version);
            return redirect()->route('app.show', [$id]);
        }
        else
            return back()->withErrors('Failed to delete data');
    }
}
