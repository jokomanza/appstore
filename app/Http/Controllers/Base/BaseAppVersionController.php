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
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|RedirectResponse|View
     */
    public function create(Request $request, $packageName = null)
    {
        if ($request->routeIs($this->getUserType() . '.client.version.create')) {
            $packageName = config('app.client_package_name');
        } else {
            if ($packageName == null) return back()->withErrors("Package name parameter was null");
        }

        $app = App::where('package_name', $packageName)->first();

        if (!$app) return back()->withErrors("App not found");

        return view($this->getUserType() . '.apps.versions.create', compact('app'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAppVersionRequest $request
     * @param $packageName
     * @return View|RedirectResponse
     * @throws XmlParserException
     */
    public function store(CreateAppVersionRequest $request, $packageName)
    {
        $app = App::where('package_name', $packageName)->first();

        if ($app == null) back()->withErrors('Target app not found');

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

        if (!empty(AppVersion::where(['app_id' => $app->id, 'version_code' => $version_code])->first())) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name already exists"
            ];
        }
        $maxVersionNumber = AppVersion::where(['app_id' => $app->id])->max('version_code');
        if ($maxVersionNumber >= $version_code) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name must greater than $maxVersionNumber "
            ];
        }
        if (!empty(AppVersion::where(['app_id' => $app->id, 'version_name' => $version_name])->first())) {
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
        $appVersion->app_id = $app->id;
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

            return back()->withErrors("Failed to save app version");
        }

        if ($request->routeIs($this->getUserType() . '.client.version.store')) {
            return redirect()->route($this->getUserType() . '.client.show')
                ->with('messages', ['Successfully release new version']);
        } else {
            return redirect()->route($this->getUserType() . '.app.show', $packageName)
                ->with('messages', ['Successfully release new version']);
        }

    }

    public function showClient(Request $request, $versionName) {
        return $this->show($request, null, $versionName);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param null $packageName
     * @param $versionName
     * @return Factory|Application|RedirectResponse|View
     */
    public function show(Request $request, $packageName = null, $versionName)
    {
        if ($request->routeIs($this->getUserType() . '.client.version.show')) {
            $packageName =  config('app.client_package_name');
        } else {
            if ($packageName == config('app.client_package_name') || $packageName == null) {
                return back()->withErrors('Package name is incorrect');
            }
        }

        $app = App::where('package_name', $packageName)
            ->first();

        if (!$app) return back()->withErrors("Application not found");

        $version = $app->getVersion($versionName);
        if (!$version) return back()->withErrors('Version not found');

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) {
            return back()->withErrors("You don't have permission to perform this action");
        }

        return view($this->getUserType() . '.apps.versions.show', compact('app', 'version', 'isAppDeveloper', 'isAppOwner'));
    }

    public function editClient(Request $request, $versionName) {
        return $this->edit($request, null, $versionName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param $versionName
     * @param null $packageName
     * @return RedirectResponse|View
     */
    public function edit(Request $request, $packageName = null, $versionName)
    {
        if ($request->routeIs($this->getUserType() . '.client.version.edit')) {
            $packageName = config('app.client_package_name');
        } else {
            if ($packageName == config('app.client_package_name') || $packageName == null) {
                return back()->withErrors('Package name is incorrect');
            }
        }

        $version = AppVersion::with('app')->where(['version_name' => $versionName])
            ->whereHas('app', function ($q) use ($packageName) {
                $q->where('package_name', $packageName);
            })->first();

        if (!$version) return back()->withErrors("App or Version not found");

        $app = $version->app;

        return view($this->getUserType() . '.apps.versions.edit', compact('app', 'version'));
    }

    public function updateClient(UpdateAppVersionRequest $request, $versionName) {
        return $this->update($request, null, $versionName);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAppVersionRequest $request
     * @param $versionName
     * @param null $packageName
     * @return RedirectResponse
     */
    public function update(UpdateAppVersionRequest $request, $packageName = null, $versionName)
    {
        if ($request->routeIs($this->getUserType() . '.client.version.update')) {
            $packageName = config('app.client_package_name');
        } else {
            if ($packageName == config('app.client_package_name') || $packageName == null) {
                return back()->withErrors('Package name is incorrect');
            }
        }

        $version = AppVersion::where(['version_name' => $versionName])
            ->whereHas('app', function ($q) use ($packageName) {
                $q->where('package_name', $packageName);
            })->first();

        if (!isset($version)) return back()->withErrors("Version not found");

        $version->description = $request->description;

        if ($version->update()) {
            if ($request->routeIs($this->getUserType() . '.client.version.update')) {
                return redirect()->route($this->getUserType() . '.client.version.show', $versionName)
                    ->with('messages', ['Successfully update description']);
            } else {
                return redirect()->route($this->getUserType() . '.version.show', [$packageName, $versionName])
                    ->with('messages', ['Successfully update description']);
            }
        } else return back()->withErrors('Failed to update data');
    }


    /**
     * @throws Exception
     */
    public function destroyClient(Request $request, $versionName) {
        $this->destroy($request, config('app.client_package_name'), $versionName);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $packageName
     * @param $versionName
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Request $request, $packageName, $versionName)
    {
        $version = AppVersion::where('version_name', $versionName)->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->first();

        if (!$version) return back()->withErrors('Target version not found');

        $app = $version->app;

        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);
        if (!$isAppOwner) return back()->withErrors("You can't delete this app version because you are not app owner");

        if ($version->delete()) {
            $this->service->handleDeletedVersion($version);

            if ($request->routeIs($this->getUserType() . '.client.version.destroy')) {
                return redirect()->route($this->getUserType() . '.client.show')
                    ->with('messages', ['Successfully delete app version']);
            } else {
                return redirect()->route($this->getUserType() . '.app.show', $packageName)
                    ->with('messages', ['Successfully delete app version']);
            }
        } else return back()->withErrors('Failed to delete data');
    }
}
