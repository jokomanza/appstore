<?php

namespace App\Http\Controllers;

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

/**
 * Class App Controller
 * 
 * @property AppRepositoryInterface $appRepository
 * @property AppServiceInterface $appService
 * 
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    private $appRepository;
    private $appService;

    // AppRepositoryInterface $appRepository
    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        $this->middleware('auth');
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
        return view('apps.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('apps.create');
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
            $app->icon_url = $iconUrl;
            $app->user_documentation_url = $userDocUrl;
            $app->developer_documentation_url = $devDocUrl;

            if ($app->save()) {
                return redirect()->route('app.show', [$app->id]);
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

            $developer = new Developer();
            $developer->fill($request->all());

            if ($developer->save()) {
                return redirect()->route('app.show', [$request->app_id]);
            }
            else {
                dd(false);
                throw new \Exception("failed to add developer");
            }
        }
        catch (\Exception $e) {
            return back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $maxAccessLevel = Auth::user()->access_level;

            $data = $this->appRepository->getAppById($id);
            $data = App::with('app_versions')->find($id);
            $developers = Developer::with('user')->where('app_id', $id)->get();
            $allowedDevelopers = User::where('access_level', '<=', $maxAccessLevel)->get(['registration_number'])
            ->map(function ($value) {
                return [$value->registration_number => $value->registration_number];
            });
            // dd($developers);
        }
        catch (\Exception $e) {
            return view('errors.404');
        }

        return view('apps.show', ['data' => $data, 'developers' => $developers, 'allowedDevelopers' => $allowedDevelopers]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->appRepository->getAppById($id);

        return view('apps.edit', ['data' => $data]);
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
            $app->fill($request->all());
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

                return redirect()->route('app.show', $app->id);
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

            
            return redirect()->route('app.index');
        }
        else
            return back()->withErrors('Failed to delete data');
    }

    public function removeDeveloper($id, $registrationNumber)
    {
        $developer = Developer::where(['app_id' => $id, 'user_registration_number' => $registrationNumber])->first();

        if (!isset($developer)) {
            return back()->withErrors('developer not found');
        }

        if ($developer->delete()) {
            return redirect()->route('app.show', [$id]);
        }
        else
            return back()->withErrors('Failed to delete data');
    }
}
