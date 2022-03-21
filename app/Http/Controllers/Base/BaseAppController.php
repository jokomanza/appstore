<?php

namespace App\Http\Controllers\Base;

use App\Http\Requests\AddDeveloperRequest;
use App\Http\Requests\CreateAppRequest;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;
use App\Models\App;
use App\Models\AppVersion;
use App\Models\Permission;
use App\Models\Report;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Class Base App Controller
 *
 * @property AppRepositoryInterface $appRepository
 * @property AppServiceInterface $appService
 *
 * @package App\Http\Controllers\Admin
 */
abstract class BaseAppController extends BaseController
{
    protected $appRepository;
    protected $appService;

    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        $this->appRepository = $appRepository;
        $this->appService = $appService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view($this->getView() . '.apps.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        if ($this->getView() != 'admin') return view($this->getView() . '.errors.404');

        return view($this->getView() . '.apps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAppRequest $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function store(CreateAppRequest $request)
    {
        if ($this->getView() != 'admin') return view($this->getView() . '.errors.404');

        $time = time();

        try {
            $iconUrl = $this->appService->handleUploadedIcon($request->package_name, $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->package_name, $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->package_name, $request->file('developer_documentation_file'), $time);

            $app = new App();
            $app->fill($request->all());
            $app->api_token = str_random(128);
            $app->icon_url = $iconUrl;
            $app->user_documentation_url = $userDocUrl;
            $app->developer_documentation_url = $devDocUrl;

            if ($app->save()) {
                return redirect()->route($this->getView() . '.app.show', [$app->package_name]);
            } else
                throw new Exception("failed to save app");
        } catch (Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Add new permission to current app, either developer or owner
     *
     * @param AddDeveloperRequest $request
     * @return RedirectResponse
     */
    public function addPermission(AddDeveloperRequest $request)
    {
        try {
            $permission = new Permission();
            $permission->fill($request->all());

            if ($permission->save()) return back();
            else throw new Exception("failed to add permission");
        } catch (Exception $e) {
            if ($e->getCode() == 23505) return back()->withErrors('Data already exists');

            return back()->withErrors($e->getCode())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function show(Request $request, $packageName = null)
    {
        try {
            if ($request->routeIs($this->getView() . '.client.show')) {
                $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
            } else {
                if ($packageName == null) return view($this->getView() . '.errors.404');

                $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $packageName)->first();
                if ($app == null) return view($this->getView() . '.errors.404');
            }

            $isAppDeveloper = Auth::user()->isDeveloperOf($app);
            $isAppOwner = Auth::user()->isOwnerOf($app);

            $permissions = Permission::with('user')->where('app_id', $app->id)->get();
            $allowedPersons = User::get(['registration_number'])
                ->map(function ($value) {
                    return [$value->registration_number => $value->registration_number];
                });
            $reports = Report::where('app_id', $app->id)->get();

        } catch (Exception $e) {
            return back()->withException($e);
        }

        return view($this->getView() . '.apps.show', compact('app', 'permissions', 'allowedPersons', 'reports', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param null $packageName
     * @return Factory|Application|RedirectResponse|View
     */
    public function edit(Request $request, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.edit')) {
            $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
        } else {
            if ($packageName == null) return view($this->getView() . '.errors.404');

            $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $packageName)->first();
            if ($app == null) return view($this->getView() . '.errors.404');
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getView() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) return back()->withErrors("You don't have permission to perform this action");

        return view($this->getView() . '.apps.edit', compact('app', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param null $packageName
     * @return Factory|Application|RedirectResponse|View
     */
    public function update(Request $request, $packageName = null)
    {
        if ($request->routeIs($this->getView() . '.client.show')) {
            $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
        } else {
            if ($packageName == null) return view($this->getView() . '.errors.404');

            $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $packageName)->first();
            if ($app == null) return view($this->getView() . '.errors.404');
        }

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getView() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) return back()->withErrors("You don't have permission to perform this action");

        $time = time();

        try {
            $iconUrl = $this->appService->handleUploadedIcon($request->package_name, $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->package_name, $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->package_name, $request->file('developer_documentation_file'), $time);

            if ($isAppOwner) $fields = $request->all();
            else $fields = $request->except('package_name');

            $app->fill($fields);
            if ($iconUrl) {
                $oldIcon = $app->icon_url;
                $app->icon_url = $iconUrl;
            }
            if ($userDocUrl) {
                $oldUserDoc = $app->user_documentation_url;
                $app->user_documentation_url = $userDocUrl;
            }
            if ($devDocUrl) {
                $oldDevDoc = $app->developer_documentation_url;
                $app->developer_documentation_url = $devDocUrl;
            }

            if (!$app->isDirty()) return back()->withInput()->withErrors("Data has not changed");

            if ($app->update()) {
                @unlink(public_path('/storage/') . $oldIcon);
                @unlink(public_path('/storage/') . $oldUserDoc);
                @unlink(public_path('/storage/') . $oldDevDoc);

                if ($request->routeIs($this->getView() . '.client.update')) return redirect()->route('admin.client.show');
                else return redirect()->route($this->getView() . '.app.show', $app->package_name);
            } else throw new Exception("Failed to update data");
        } catch (Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $packageName
     * @return RedirectResponse
     */
    public function destroy(Request $request, $packageName)
    {
        $app = App::where('package_name', $packageName)->first();

        if (!isset($app)) return back()->withErrors('application not found');

        if ($app->package_name == config('app.client_package_name')) return back()->withErrors("You can't delete client app");

        if ($this->getView() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);
        if (!$isAppOwner) return back()->withErrors("You can't delete this app because you are not app owner");

        $versions = AppVersion::where('app_id', $app->id)->get();

        if ($this->appRepository->deleteApp($app->id)) {
            $this->appService->handleDeletedApp($app, $versions);

            return redirect()->route($this->getView() . '.app.index');
        } else return back()->withErrors('Failed to delete data');
    }

    /**
     * Remove user permission to this app
     *
     * @param $id
     * @param $registrationNumber
     * @return RedirectResponse
     * @throws Exception
     */
    public function removePermission($id, $registrationNumber)
    {
        $developer = Permission::where(['app_id' => $id, 'user_registration_number' => $registrationNumber])->first();

        if (!isset($developer)) return back()->withErrors('developer not found');

        if ($developer->delete()) return back();
        else return back()->withErrors('Failed to delete data');
    }

    public function getDataTables(Request $request)
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
                $show = route($this->getView() . '.app.show', $app->package_name);
                $edit = route($this->getView() . '.app.edit', $app->package_name);

                $isAppDeveloper = Auth::user()->isDeveloperOf($app);
                $isAppOwner = Auth::user()->isOwnerOf($app);

                $nestedData['id'] = $app->id;
                $nestedData['name'] = $app->name;
                $nestedData['package_name'] = $app->package_name;
                $nestedData['updated_at'] = (new Carbon($app->updated_at))->diffForHumans();
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>";
                if ($isAppDeveloper || $isAppOwner || $this->getView() == 'admin') $nestedData['options'] .= "&emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
                $data[] = $nestedData;
            }
        }

        $result = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalData,
            "recordsFiltered" => $totalFiltered,
            "data" => $data
        );

        return response()->json($result);
    }

    private function validatePermission(App $app, $redirect = true)
    {
        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        $isAppOwner = Auth::user()->isOwnerOf($app);

        if ($this->getView() == 'user') {
            if (!$isAppDeveloper && !$isAppOwner) {
                if ($redirect) return back()->withErrors("You don't have permission to perform this action");
                else return unauthenticated();
            }
        }
    }

}
