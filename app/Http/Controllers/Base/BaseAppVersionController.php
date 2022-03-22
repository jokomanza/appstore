<?php

namespace App\Http\Controllers\Base;

use ApkParser\Exceptions\XmlParserException;
use ApkParser\Parser;
use App\Http\Controllers\Base\Controller;
use App\Http\Requests\CreateAppVersionRequest;
use App\Http\Requests\UpdateAppVersionRequest;
use App\Interfaces\AppVersionServiceInterface;
use App\Models\App;
use App\Models\AppVersion;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

abstract class BaseAppVersionController extends BaseController
{
    private $service;

    public function __construct(AppVersionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(Request $request, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.version.create')) {
            $app = App::where('package_name', 'com.quick.quickappstore')->first();
        } else {
            $app = App::where('package_name', $packageName)->first();
        }

        if (!$app) return view($this->getView() . '.errors.404');

        return view($this->getView() . '.apps.versions.create')->with(['app' => $app]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAppVersionRequest $request
     * @param $id
     * @return View|RedirectResponse
     * @throws XmlParserException
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

        $apk = new Parser(public_path('/storage/' . $storedApkName));

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

        return redirect()->route('app.show', [$id]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param null $packageName
     * @param $versionName
     * @return Factory|Application|RedirectResponse|View
     */
    public function show(Request $request, $versionName, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.version.show')) {
            $app = App::where('package_name', 'com.quick.quickappstore')->first();
        } else {
            if ($packageName == 'com.quick.quickappstore') return view($this->getView() . '.errors.404');

            $app = App::where('package_name', $packageName)
                ->first();
        }

        if (!$app) return view($this->getView() . '.errors.404');

        $version = $app->getVersion($versionName);
        if (!$version) return view($this->getView() . '.errors.404', ['message' => 'version not found']);

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getView() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) return back()->withErrors("You don't have permission to perform this action");

        return view($this->getView() . '.apps.versions.show', compact('app', 'version', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param $versionName
     * @param null $packageName
     * @return RedirectResponse|View
     */
    public function edit(Request $request, $versionName, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.version.edit')) {
            $packageName = config('app.client_package_name');
        }

        $version = AppVersion::with('app')->where(['version_name' => $versionName])
            ->whereHas('app', function ($q) use ($packageName) {
                $q->where('package_name', $packageName);
            })->first();

        if (!$version) return back()->withErrors("App or Version not found");

        $app = $version->app;

        return view($this->getView() . '.apps.versions.edit', compact('app', 'version'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAppVersionRequest $request
     * @param $versionName
     * @param null $packageName
     * @return RedirectResponse
     */
    public function update(UpdateAppVersionRequest $request, $versionName, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.version.update')) {
            $packageName = config('app.client_package_name');
        }

        $version = AppVersion::where(['version_name' => $versionName])
            ->whereHas('app', function ($q) use ($packageName) {
                $q->where('package_name', $packageName);
            })->first();

        if (!isset($version)) return back()->withErrors("Version not found");

        $version->description = $request->description;

        if ($version->update()) {
            if ($request->routeIs($this->getView() . '.client.version.update')) {
                return redirect()->route($this->getView() . '.client.version.show', $versionName);
            } else {
                return redirect()->route($this->getView() . '.version.show', [$packageName, $versionName]);
            }
        } else return back()->withErrors('Failed to update data');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $packageName
     * @param $versionName
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy($packageName, $versionName)
    {
        $version = AppVersion::where('version_name', $versionName)->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->first();

        if (!$version) return back()->withErrors('Target version not found');

        if ($this->getView() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);
        if (!$isAppOwner) return back()->withErrors("You can't delete this app version because you are not app owner");

        if ($version->delete()) {
            $this->service->handleDeletedVersion($version);
            return redirect()->route('app.show', [$id]);
        } else return back()->withErrors('Failed to delete data');
    }
}
