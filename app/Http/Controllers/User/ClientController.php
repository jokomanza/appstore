<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;
use App\Interfaces\AppRepositoryInterface;
use App\Interfaces\AppServiceInterface;
use App\Models\App;
use App\Models\Developer;
use App\Models\Report;
use App\User;
use DomainException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    private $appRepository;
    private $appService;

    public function __construct(AppRepositoryInterface $appRepository, AppServiceInterface $appService)
    {
        $this->middleware('auth');
        $this->appRepository = $appRepository;
        $this->appService = $appService;
    }

    public function index(Request $request)
    {
        try {
            $maxAccessLevel = Auth::user()->access_level;

            $data = App::with('app_versions')->where('package_name', 'com.quick.quickappstore')->first();
            $developers = Developer::with('user')->where('app_id', $data->id)->get();
            $allowedDevelopers = User::where('access_level', '<=', $maxAccessLevel)->get(['registration_number'])
                ->map(function ($value) {
                    return [$value->registration_number => $value->registration_number];
                });
            $reports = Report::where('app_id', $data->id)->get();
        } catch (Exception $e) {
            return view('errors.404');
        }

        return view('clients.index', compact('data', 'developers', 'allowedDevelopers', 'reports'));
    }

    public function edit(Request $request)
    {
        $data = App::where('package_name', 'com.quick.quickappstore')->first();

        return view('clients.edit', ['data' => $data]);
    }

    public function update(Request $request)
    {

        $time = time();

        try {
            $iconUrl = $this->appService->handleUploadedIcon($request->package_name, $request->file('icon_file'), $time);
            $userDocUrl = $this->appService->handleUploadedUserDocumentation($request->package_name, $request->file('user_documentation_file'), $time);
            $devDocUrl = $this->appService->handleUploadedDeveloperDocumentation($request->package_name, $request->file('developer_documentation_file'), $time);

            $app = App::where('package_name', 'com.quick.quickappstore')->first();
            $app->fill($request->except('package_name'));
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

                if ($iconUrl) @unlink(public_path('/storage/') . $oldIcon);
                if ($userDocUrl) @unlink(public_path('/storage/') . $oldUserDoc);
                if ($devDocUrl) @unlink(public_path('/storage/') . $oldDevDoc);

                return redirect()->route('client.index');
            } else
                throw new DomainException("Failed to update data");
        } catch (Exception $e) {
            $this->appService->handleUploadedFileWhenFailed($request->package_name, $time);

            return back()->withErrors($e->getMessage())->withInput();
        }
    }
}
