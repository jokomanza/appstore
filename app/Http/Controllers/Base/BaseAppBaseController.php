<?php

namespace App\Http\Controllers\Base;

use App\Http\Requests\CreateAppRequest;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;
use App\Models\App;
use App\Repositories\AppRepository;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\AppVersion;
use App\User;
use App\Models\Developer;
use App\Http\Requests\AddDeveloperRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\View\View;

/**
 * Class Base App Controller
 *
 * @property AppRepositoryInterface $appRepository
 * @property AppServiceInterface $appService
 *
 * @package App\Http\Controllers\Admin
 */
abstract class BaseAppBaseController extends BaseController
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
        return view($this->getView() . '.apps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAppRequest $request
     * @return RedirectResponse
     */
    public function store(CreateAppRequest $request)
    {
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
                return redirect()->route($this->getView() . '.app.show', [$app->id]);
            } else
                throw new \Exception("failed to save app");
        } catch (\Exception $e) {
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
            else throw new \Exception("failed to add permission");
        } catch (\Exception $e) {
            if ($e->getCode() == 23505) return back()->withErrors('Data already exists');

            return back()->withErrors($e->getCode())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Request $request)
    {
        try {
            if ($request->routeIs($this->getView() . '.client.show')) {
                $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
            } else {
                $package_name = $request->package_name;
                if ($package_name == null) return view($this->getView() . '.errors.404');

                $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $package_name)->first();
                if ($app == null) return view($this->getView() . '.errors.404');
            }

            $isAppDeveloper = Auth::user()->isDeveloperOf($app);
            $isAppOwner = Auth::user()->isOwnerOf($app);

            $permissions = Permission::with('user')->where('app_id', $package_name ?: $app->package_name)->get();
            $allowedPersons = User::get(['registration_number'])
                ->map(function ($value) {
                    return [$value->registration_number => $value->registration_number];
                });
            $reports = Report::where('app_id', $app->id)->get();
        } catch (\Exception $e) {
            return view($this->getView() . '.errors.404');
        }

        return view($this->getView() . '.apps.show', compact('app', 'permissions', 'allowedPersons', 'reports', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @return Factory|Application|View
     */
    public function edit(Request $request)
    {
        if ($request->routeIs($this->getView() . '.client.show')) {
            $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
        } else {
            $package_name = $request->package_name;
            if ($package_name == null) return view($this->getView() . '.errors.404');

            $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $package_name)->first();
            if ($app == null) return view($this->getView() . '.errors.404');
        }

        return view($this->getView() . '.apps.edit', compact('app'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function update(Request $request)
    {
        if ($request->routeIs($this->getView() . '.client.show')) {
            $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
        } else {
            $package_name = $request->package_name;
            if ($package_name == null) return view($this->getView() . '.errors.404');

            $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('package_name', $package_name)->first();
            if ($app == null) return view($this->getView() . '.errors.404');
        }

        $time = time();

        try {
            $iconUrl = $this->appService->handleUploadedIcon($request->package_name, $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->package_name, $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->package_name, $request->file('developer_documentation_file'), $time);

            $fields = $request->all();

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
                else return redirect()->route($this->getView() . '.app.show', $app->id);
            } else throw new \Exception("Failed to update data");
        } catch (\Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        $app = App::where('package_name', $request->package_name);

        if (!isset($app)) return back()->withErrors('application not found');

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
     * @throws \Exception
     */
    public function removePermission($id, $registrationNumber)
    {
        $developer = Permission::where(['app_id' => $id, 'user_registration_number' => $registrationNumber])->first();

        if (!isset($developer)) return back()->withErrors('developer not found');

        if ($developer->delete()) return back();
        else return back()->withErrors('Failed to delete data');
    }


    public function getReportsDataTables(Request $request, $id)
    {
        $app = App::find($id);

        $columns = [
            0 => 'created_at',
            1 => 'app_version_name',
            2 => 'android_version',
            3 => 'device',
            4 => 'exception',
            5 => 'action',
        ];

        $totalData = Report::with('app')->whereHas('app', function ($q) use ($id) {
            $q->where('id', $id);
        })->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $reports = Report::with('app')->whereHas('app', function ($q) use ($id) {
                $q->where('id', $id);
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $reports = Report::with('app')->whereHas('app', function ($q) use ($id) {
                $q->where('id', $id);
            })->where('app_version_name', 'LIKE', "%$search%")
                ->orWhere('android_version', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('exception', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Report::with('app')->whereHas('app', function ($q) use ($id) {
                $q->where('id', $id);
            })->where('app_version_name', 'LIKE', "%$search%")
                ->orWhere('android_version', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('exception', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($reports)) {
            foreach ($reports as $report) {
                $edit = route($this->getView() . '.report.show', [$app->package_name, $report->id]);
                $nestedData['date'] = $report->created_at->diffForHumans();
                $nestedData['app_version'] = $report->app_version_name;
                $nestedData['android_version'] = $report->android_version;
                $nestedData['device'] = $report->brand . ' ' . $report->phone_model;
                $nestedData['exception'] = $report->exception;

                $nestedData['options'] = "<a href='$edit' class='btn btn-success' >Show</a>";
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
                $show = route($this->getView() . '.app.show', $app->id);
                $edit = route($this->getView() . '.app.edit', $app->id);
                $nestedData['id'] = $app->id;
                $nestedData['name'] = $app->name;
                $nestedData['package_name'] = $app->package_name;
                $nestedData['updated_at'] = (new Carbon($app->updated_at))->diffForHumans();
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>
                      &emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
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

}
