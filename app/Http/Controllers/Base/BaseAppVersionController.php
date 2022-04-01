<?php

namespace App\Http\Controllers\Base;

use ApkParser\Parser;
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

/**
 * Base app version controller.
 *
 * @property AppVersionServiceInterface $service
 */
abstract class BaseAppVersionController extends BaseController
{

    /**
     * App version service implementation.
     *
     * @var AppVersionServiceInterface
     */
    private $service;

    /**
     * Determine is the current route is for client app or not.
     *
     * @var bool
     */
    private $isClientRoute;

    /**
     * Create a controller instance.
     *
     * @param AppVersionServiceInterface $service
     */
    public function __construct(AppVersionServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|Application|RedirectResponse|View
     */
    public function create(Request $request, $packageName)
    {
        $app = (new App)->getApp($packageName);

        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors("App not found");
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    $packageName
                )
                ->withErrors("You can't create new version, because you're not owner or developer of this app");
        }

        return view($this->getUserType() . '.apps.versions.create', compact('app'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAppVersionRequest $request
     * @param $packageName
     * @return View|RedirectResponse
     */
    public function store(CreateAppVersionRequest $request, $packageName)
    {
        $app = (new App)->getApp($packageName);

        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors('The applicationlication you want to make the version, was not found');
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    $packageName
                )
                ->withErrors("You can't create new version, because you're not owner or developer of this app");
        }

        $time = time();

        $storedApk = $this->service->handleUploadedApk($app->package_name, $request->file('apk_file'), $time);
        $storedApkName = $storedApk['name'];
        $storedApkSize = $storedApk['size'];
        $storedIconName = $this->service->handleUploadedIcon($app->package_name, $request->file('icon_file'), $time);

        try {
            $apk = new Parser(public_path('/storage/' . $storedApkName));

            $manifest = $apk->getManifest();
            $package_name = $manifest->getPackageName();
            $version_code = $manifest->getVersionCode();
            $version_name = $manifest->getVersionName();
            $min_sdk_level = $manifest->getMinSdkLevel();
            $target_sdk_level = $manifest->getTargetSdkLevel();

        } catch (Exception $e) {
            @unlink(public_path('/storage/') . $storedApkName);
            if ($storedIconName) @unlink(public_path('/storage/') . $storedIconName);

            return redirect()
                ->route(
                    $this->getUserType() . '.app.version.create',
                    $packageName
                )
                ->withErrors($e->getMessage())
                ->withInput();
        }

        $additionalError = [];

        // Additional validation
        /*if ($app->package_name != $package_name) {
            $additionalError['package_name'] = [
                "package name uploaded file (" . $package_name . ") not match with current app package name (" . $app->package_name . ")"
            ];
        }*/

        if (!preg_match("/^\d{1,5}.\d{1,5}.\d{1,5}$/", $version_code)) {
            $additionalError['version_code'] = [
                "Version code invalid"
            ];
        }

        if (!empty(AppVersion::where(['application_id' => $app->id, 'version_code' => $version_code])->first())) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name already exists"
            ];
        }
        $maxVersionNumber = AppVersion::where(['application_id' => $app->id])->max('version_code');
        if ($maxVersionNumber >= $version_code) {
            $additionalError['version_code'] = [
                "Version number " . $version_code . " for app $app->name must greater than $maxVersionNumber "
            ];
        }
        if (!empty(AppVersion::where(['application_id' => $app->id, 'version_name' => $version_name])->first())) {
            $additionalError['version_name'] = [
                "Version name " . $version_name . " for app $app->name already exists"
            ];
        }

        if (!empty($additionalError)) {
            @unlink(public_path('/storage/') . $storedApkName);
            if ($storedIconName) @unlink(public_path('/storage/') . $storedIconName);

            return redirect()
                ->route(
                    $this->getUserType() . '.app.version.create',
                    $packageName
                )
                ->withErrors($additionalError)
                ->withInput();
        }

        $finalApkName = $package_name . '.' . $version_code . '.' . time() . '.apk';
        File::move(public_path('/storage/') . $storedApkName, public_path('/storage/') . $finalApkName);
        $storedApkName = $finalApkName;

        if ($storedIconName) {
            $finalIconName = $package_name . '.' . $version_code . '.icon.' . time() . '.jpg';
            File::move(public_path('/storage/') . $storedIconName, public_path('/storage/') . $finalIconName);
            $storedIconName = $finalIconName;
        }

        $applicationVersion = new AppVersion();
        $applicationVersion->application_id = $app->id;
        $applicationVersion->version_code = $version_code;
        $applicationVersion->version_name = $version_name;
        $applicationVersion->min_sdk_level = $min_sdk_level;
        $applicationVersion->target_sdk_level = $target_sdk_level;
        $applicationVersion->apk_file_url = $storedApkName;
        $applicationVersion->apk_file_size = $storedApkSize;
        $applicationVersion->icon_url = $storedIconName;
        $applicationVersion->description = $request->description;
        if (!$applicationVersion->save()) {
            @unlink(public_path('/storage/') . $storedApkName);
            if ($storedIconName) @unlink(public_path('/storage/') . $storedIconName);

            return redirect()
                ->route(
                    $this->getUserType() . '.app.version.create',
                    $packageName
                )
                ->withErrors("Failed to save new version")
                ->withInput();
        }

        return redirect()->route($this->getUserType() . '.app.show', $packageName)
            ->with('messages', ['Successfully release new version']);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param string $packageName
     * @param $versionName
     * @return Factory|Application|RedirectResponse|View
     */
    public
    function show(Request $request, $packageName, $versionName)
    {
        $app = (new App)->getApp($packageName);
        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors("Application not found");
        }

        $version = $app->getVersion($versionName);
        if ($version == null) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    $packageName
                )
                ->withErrors('Version not found');
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        return view($this->getUserType() . '.apps.versions.show', compact('app', 'version', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Show edit page.
     *
     * @param Request $request
     * @param $packageName
     * @param $versionName
     * @return Factory|Application|RedirectResponse|View
     */
    public
    function edit(Request $request, $packageName, $versionName)
    {
        $app = (new App)->getApp($packageName);
        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors("Application not found");
        }

        $version = $app->getVersion($versionName);
        if ($version == null) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    [$packageName, $versionName]
                )
                ->withErrors('Version not found');
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) {
            return redirect()
                ->route(
                    $this->getUserType() . '.version.show',
                    [$packageName, $versionName]
                )
                ->withErrors("You don't have permission to edit this version");
        }

        return view($this->getUserType() . '.apps.versions.edit', compact('app', 'version'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAppVersionRequest $request
     * @param $versionName
     * @param $packageName
     * @return RedirectResponse
     */
    public
    function update(UpdateAppVersionRequest $request, $packageName, $versionName)
    {
        $app = (new App)->getApp($packageName);
        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors("Application not found");
        }

        $version = $app->getVersion($versionName);
        if ($version == null) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    $packageName
                )
                ->withErrors('Version not found');
        }

        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);
        $isAppDeveloper = Auth::user()->isDeveloperOf($app);

        if (!$isAppOwner || !$isAppDeveloper) {
            return redirect()
                ->route(
                    $this->getUserType() . '.version.edit',
                    $packageName
                )
                ->withErrors("You can't update this app version because you are not the app developer or owner")
                ->withInput();
        }

        $version->description = $request->get('description');

        if ($version->update()) {
            return redirect()->route($this->getUserType() . '.version.show', [$packageName, $versionName])
                ->with('messages', ['Successfully update description']);
        } else {
            return redirect()
                ->route(
                    $this->getUserType() . '.version.edit',
                    $packageName
                )
                ->withErrors('Failed to update data')
                ->withInput();
        }
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
    public
    function destroy(Request $request, $packageName, $versionName)
    {
        $app = (new App)->getApp($packageName);
        if ($app == null) {
            return redirect()
                ->route($this->getUserType() . '.app.index')
                ->withErrors("Application not found");
        }

        $version = $app->getVersion($versionName);
        if ($version == null) {
            return redirect()
                ->route(
                    $this->getUserType() . '.app.show',
                    $packageName
                )
                ->withErrors('Version not found');
        }

        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppOwner) {
            return redirect()
                ->route(
                    $this->getUserType() . '.version.show',
                    $packageName
                )
                ->withErrors("You can't delete this app version because you are not the app owner");
        }

        if ($version->delete()) {
            $this->service->handleDeletedVersion($version);

            return redirect()->route($this->getUserType() . '.app.show', $packageName)
                ->with('messages', ['Successfully delete app version']);
        } else {
            return redirect()
                ->route(
                    $this->getUserType() . '.version.show',
                    $packageName
                )
                ->withErrors('Failed to delete data');
        }
    }
}
