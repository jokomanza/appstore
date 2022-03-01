<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;
use App\Models\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AppVersionController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $app = App::find($id);

        return view('apps.versions.create')->with(['app' => $app]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        // dd($request->all());

        $app = App::find($id);

        if ($app == null) {
            return view('errors.404');
        }

        $validator = Validator::make($request->all(), [
            'apk_file' => 'required|mimes:apk,jar,zip',
            'icon_file' => 'required|image',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return dd($validator->messages());
        }

        if ($request->hasfile('apk_file')) {
            $extension = $request->file('apk_file')->getClientOriginalExtension();
            $storedApkSize = $request->file('apk_file')->getSize();

            if ($extension != 'apk') {
                return response('apk file must have *.apk extension');
            }
            $storedApkName = $app->package_name . '.' . time() . '.apk';
            if (!$request->file('apk_file')->move(public_path('/storage/'), $storedApkName)) {
                return response('failed to save apk file');
            }
        }

        if ($request->hasfile('icon_file')) {
            $extension = $request->file('icon_file')->getClientOriginalExtension();

            $storedIconName = $app->package_name . '.icon.' . time() . ".$extension";
            if (!$request->file('icon_file')->move(public_path('/storage/'), $storedIconName)) {
                @unlink(public_path('/storage/') . $storedApkName);
                @unlink(public_path('/storage/') . $storedIconName);
                return response('failed to save icon file');
            }
        }

        $apk = new \ApkParser\Parser(public_path('/storage/' . $storedApkName));

        $manifest = $apk->getManifest();
        $permissions = $manifest->getPermissions();
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
                "Version number " . $version_code . " for app with id $id already exists"
            ];
        }
        $maxVersionNumber = AppVersion::where(['app_id' => $id])->max('version_code');
        if ($maxVersionNumber >= $version_code) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app with id $id must greater than $maxVersionNumber "
            ];
        }
        if (!empty(AppVersion::where(['app_id' => $id, 'version_name' => $version_name])->first())) {
            $additionalError['version_name'] = [
                "Version name " . $version_name . " for app with id $id already exists"
            ];
        }

        if (!empty($additionalError)) {
            @unlink(public_path('/storage/') . $storedApkName);
            @unlink(public_path('/storage/') . $storedIconName);
            // dd($additionalError);
            return back()->withErrors($additionalError)->withInput();
        }

        $finalApkName = $package_name . '.' . $version_code . '.' . time() . '.apk';
        File::move(public_path('/storage/') . $storedApkName, public_path('/storage/') . $finalApkName);

        $storedApkName = $finalApkName;

        DB::beginTransaction();

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
            DB::rollback();
            return response("failed to save app version");
        }

        DB::commit();

        return view('version.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function show($id, $idVersion)
    {
        $app = App::find($id);
        $version = AppVersion::find($idVersion);
        return view('apps.versions.show')->with(['app' => $app, 'version' => $version]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function edit(AppVersion $appVersion)
    {
    //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppVersion $appVersion)
    {
    //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AppVersion  $appVersion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $versionId)
    {

        if (!AppVersion::where(['app_id' => $id, 'id' => $versionId])->first()) {
            return back()->withErrors('Target version not found');
        }
        if (AppVersion::destroy($versionId)) {
            return redirect()->route('app.show', $id);
        }
        else
            return back()->withErrors('Failed to delete data');
    }
}
