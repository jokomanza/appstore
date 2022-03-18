<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateAppRequest;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;
use App\Models\App;
use App\Repositories\AppRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AppVersion;
use App\User;
use App\Models\Developer;
use App\Http\Requests\AddDeveloperRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

/**
 * Class App Controller
 * 
 * @property AppRepositoryInterface $appRepository
 * @property AppServiceInterface $appService
 * 
 * @package App\Http\Controllers\Admin
 */
class AppController extends Controller
{
    private $appRepository;
    private $appService;
    private $isAppDeveloper;

    // AppRepositoryInterface $appRepository
    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        $this->middleware('auth:admin');
        $this->appRepository = $appRepository;
        $this->appService = $appService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->appRepository->paginate(10);
        return view('apps.admin.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateAppRequest  $request
     * @return \Illuminate\Http\Response
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
                return redirect()->route('admin.app.show', [$app->id]);
            }
            else
                throw new \Exception("failed to save app");
        }
        catch (\Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function addDeveloper(AddDeveloperRequest $request)
    {
        try {

            $developer = new Permission();
            $developer->fill($request->all());

            if ($developer->save()) {
                return back();
            }
            else {
                dd(false);
                throw new \Exception("failed to add developer");
            }
        }
        catch (\Exception $e) {
            if ($e->getCode() == 23505) {
                return back()->withErrors('Data already exists');
            }
            return back()->withErrors($e->getCode())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        try {

            $id = $request->id;

            $maxAccessLevel = Auth::user()->access_level;

            if ($request->routeIs('admin.client.show')) {
                $app = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
            }
            else {
                if ($id == null)
                    return view('errors.404');

                $app = App::with('app_versions')->where('package_name', '!=', 'com.quick.quickappstore')->where('id', $id)->firstOrFail();
            }


            $isAppDeveloper = Auth::user()->isDeveloperOf($app);
            $isAppOwner = Auth::user()->isOwnerOf($app);

            $permissions = Permission::with('user')->where('app_id', $id ? $id : $app->id)->get();
            $allowedPersons = User::get(['registration_number'])
                ->map(function ($value) {
                return [$value->registration_number => $value->registration_number];
            });
            $reports = Report::where('app_id', $app->id)->get();
        }
        catch (\Exception $e) {
            return view('errors.404');
        }

        return view('apps.admin.show', compact('app', 'permissions', 'allowedPersons', 'reports', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        if ($request->routeIs('admin.client.edit')) {
            $app = App::where('package_name', 'com.quick.quickappstore')->first();
        }
        else {

            $id = $request->id;

            if ($id == null) return view('errors.404');

            $app = $this->appRepository->getAppById($id);
        }
        
        return view('apps.admin.edit', compact('app'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $time = time();

        try {
            $iconUrl = $this->appService->handleUploadedIcon($request->package_name, $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->package_name, $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->package_name, $request->file('developer_documentation_file'), $time);

            $app = App::find($id);

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

            if (!$app->isDirty()) {
                return back()->withInput()->withErrors("Data has not changed");
            }

            if ($app->update()) {

                @unlink(public_path('/storage/') . $oldIcon);
                @unlink(public_path('/storage/') . $oldUserDoc);
                @unlink(public_path('/storage/') . $oldDevDoc);

                return redirect()->route('admin.app.show', $app->id);
            }
            else
                throw new \Exception("Failed to update data");
        }
        catch (\Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $app = App::find($id);

        if (!isset($app)) {
            return back()->withErrors('application not found');
        }

        $versions = AppVersion::where('app_id', $id)->get();

        if ($this->appRepository->deleteApp($id)) {
            $this->appService->handleDeletedApp($app, $versions);


            return redirect()->route('admin.app.index');
        }
        else
            return back()->withErrors('Failed to delete data');
    }

    public function removeDeveloper($id, $registrationNumber)
    {
        $developer = Permission::where(['app_id' => $id, 'user_registration_number' => $registrationNumber])->first();

        if (!isset($developer)) {
            return back()->withErrors('developer not found');
        }

        if ($developer->delete()) {
            return back();
        }
        else
            return back()->withErrors('Failed to delete data');
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
        }
        else {
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
                $edit = route('admin.report.show', [$app->package_name, $report->id]);
                $nestedData['date'] = $report->created_at->diffForHumans();
                $nestedData['app_version'] = $report->app_version_name;
                $nestedData['android_version'] = $report->android_version;
                $nestedData['device'] = $report->brand . ' ' . $report->phone_model;
                $nestedData['exception'] = $report->exception;

                $nestedData['options'] = "<a href='$edit' class='btn btn-success' >Show</a>";
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
        }
        else {
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
                $show = route('admin.app.show', $app->id);
                $edit = route('admin.app.edit', $app->id);
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
