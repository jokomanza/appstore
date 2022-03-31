<?php

namespace App\Http\Controllers\Base;

use App\Http\Requests\AddPermissionRequest;
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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
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
    /**
     * The app repository implementation.
     *
     * @var AppRepositoryInterface
     */
    protected $appRepository;

    /**
     * The app service implementation.
     *
     * @var AppServiceInterface
     */
    protected $appService;

    /**
     * Create a new controller instance.
     *
     * @param AppRepositoryInterface $appRepository
     * @param AppServiceInterface $appService
     */
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
        return view($this->getUserType() . '.apps.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view($this->getUserType() . '.apps.create');
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
            $iconUrl = $this->appService->handleUploadedIcon($request->get('package_name'), $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->get('package_name'), $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->get('package_name'), $request->file('developer_documentation_file'), $time);

            $app = new App();
            $app->fill($request->all());
            $app->api_token = str_random(128);
            $app->icon_url = $iconUrl;
            $app->user_documentation_url = $userDocUrl;
            $app->developer_documentation_url = $devDocUrl;

            if ($app->save()) {
                return redirect()
                    ->route($this->getUserType() . '.app.show', $app->package_name)
                    ->with('messages', ['Successfully create new app']);
            } else throw new Exception("failed to save app");
        } catch (Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display detail page for client app.
     *
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function showClient(Request $request)
    {
        return $this->show($request, config('app.client_package_name'));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param null $packageName
     * @return Factory|Application|RedirectResponse|View
     */
    public function show(Request $request, $packageName)
    {
        try {

            if (!$request->routeIs($this->getUserType() . '.client.show')) {
                if ($packageName == config('app.client_package_name')) {
                    return redirect()->route($this->getUserType() . '.client.show');
                }
            }

            $app = App::with('app_versions')->where('package_name', $packageName)->firstOrFail();

            $isAppDeveloper = Auth::user()->isDeveloperOf($app);
            $isAppOwner = Auth::user()->isOwnerOf($app);

            $permissions = (new Permission)->getPermissionsWithUser($app->id);
            $allowedPersons = (new User)->getAllRegistrationNumbers();

            $reports = (new Report)->getReportsByAppId($app->id);

        } catch (ModelNotFoundException $exception) {
            return view($this->getUserType() . '.errors.404')
                ->with('message', 'App not found');
        } catch (Exception $e) {
            return back()->withException($e);
        }

        return view($this->getUserType() . '.apps.show', compact('app', 'permissions', 'allowedPersons', 'reports', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Display edit page for client app.
     *
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function editClient(Request $request)
    {
        return $this->edit($request, config('app.client_package_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param null $packageName
     * @return Factory|Application|RedirectResponse|View
     */
    public function edit(Request $request, $packageName)
    {

        if (!$request->routeIs($this->getUserType() . '.client.edit')) {
            if ($packageName == config('app.client_package_name')) {
                return redirect()->route($this->getUserType() . '.client.edit');
            }
        }

        $app = (new App)->getApp($packageName);

        if ($app == null) return view($this->getUserType() . '.errors.404')->with('message', 'Target app not found');

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);

        if (!$isAppDeveloper && !$isAppOwner) return back()->withErrors("You don't have permission to perform this action");

        return view($this->getUserType() . '.apps.edit', compact('app', 'isAppDeveloper', 'isAppOwner'));
    }

    /**
     * Update client app.
     *
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function updateClient(Request $request)
    {
        return $this->update($request);
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
        if (!$request->routeIs($this->getUserType() . '.client.update')) {
            if ($packageName == config('app.client_package_name')) {
                return redirect()->route($this->getUserType() . '.client.update');
            }
        }

        $app = (new App)->getApp($packageName);

        if ($app == null) return view($this->getUserType() . '.errors.404')->with('message', 'Target app not found');

        $isAppDeveloper = Auth::user()->isDeveloperOf($app);
        if ($this->getUserType() == 'admin') $isAppOwner = true;
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

                if ($request->routeIs($this->getUserType() . '.client.update')) {
                    return redirect()->route('admin.client.show')
                        ->with('messages', ['Successfully update app data']);
                } else {
                    return redirect()->route($this->getUserType() . '.app.show', $app->package_name)
                        ->with('messages', ['Successfully update app data']);
                }
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
        if ($packageName == config('app.client_package_name')) {
            return back()->withErrors("You can't perform this action");
        }

        $app = (new App)->getApp($packageName);

        if ($app == null) return back()->withErrors("Application not found");

        if ($this->getUserType() == 'admin') $isAppOwner = true;
        else $isAppOwner = Auth::user()->isOwnerOf($app);
        if (!$isAppOwner) return back()->withErrors("You can't delete this app because you are not app owner");

        $versions = (new AppVersion)->getVersions($app->id);

        if ($this->appRepository->deleteApp($app->id)) {
            $this->appService->handleDeletedApp($app, $versions);

            return redirect()->route($this->getUserType() . '.app.index')->with('messages', ['Successfully delete app']);
        } else return back()->withErrors('Failed to delete data');
    }

    /**
     * Add new permission to current app, either developer or owner.
     *
     * @param AddPermissionRequest $request
     * @return RedirectResponse
     */
    public function addPermission(AddPermissionRequest $request)
    {
        $alreadyExists = (new Permission)->hasPermission($request->get('app_id'), $request->get('user_registration_number'));

        if ($alreadyExists) return back()->withErrors('User already added');

        $permission = new Permission();
        $permission->fill($request->all());

        if ($permission->save()) return back()->with('messages', ['Success add person to this project']);
        else return back()->withErrors('Failed to add permission')->withInput();
    }

    /**
     * Remove user permission to this app.
     *
     * @param $packageName
     * @param $registrationNumber
     * @return RedirectResponse
     * @throws Exception
     */
    public function removePermission($packageName, $registrationNumber)
    {
        $permission = (new Permission)->getPermission($packageName, $registrationNumber);

        if (!isset($permission)) return back()->withErrors('Permission not found');

        if ($permission->delete()) return back()->with('messages', ['Successfully remove person']);
        else return back()->withErrors('Failed to delete data');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataTables(Request $request)
    {

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'package_name',
            3 => 'updated_at',
        ];

        $totalData = App::where('package_name', '!=', config('app.client_package_name'))->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $apps = App::offset($start)
                ->where('package_name', '!=', config('app.client_package_name'))
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $apps = App::where('package_name', 'LIKE', "%$search%")
                ->where('package_name', '!=', config('app.client_package_name'))
                ->orWhere('name', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = App::where('package_name', 'LIKE', "%$search%")
                ->where('package_name', '!=', config('app.client_package_name'))
                ->orWhere('name', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($apps)) {
            foreach ($apps as $app) {
                $show = route($this->getUserType() . '.app.show', $app->package_name);
                $edit = route($this->getUserType() . '.app.edit', $app->package_name);

                $isAppDeveloper = Auth::user()->isDeveloperOf($app);
                $isAppOwner = Auth::user()->isOwnerOf($app);

                $iconUrl = asset('storage/' . $app->icon_url);

                $nestedData['icon'] = "&emsp;<img src='$iconUrl' width='50' height='50' alt='App icon'>";
                $nestedData['id'] = $app->id;
                $nestedData['name'] = $app->name;
                $nestedData['package_name'] = $app->package_name;
                $nestedData['updated_at'] = (new Carbon($app->updated_at))->diffForHumans();
                $nestedData['options'] = "&emsp;<a href='$show' class='btn btn-secondary'>Show</a>";
                if ($isAppDeveloper || $isAppOwner || $this->getUserType() == 'admin') $nestedData['options'] .= "&emsp;<a href='$edit' class='btn btn-success' >Edit</a>";
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
