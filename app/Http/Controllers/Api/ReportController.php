<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\App;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Models\AppVersion;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Models\Developer;
use App\Http\Requests\Api\GetReportsRequest;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NewReportNotification;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:report-api')->except(['getDataTables']);
    }
    public function index(Request $request)
    {

        $app = $request->user();

        $reports = Report::where('app_id', $app->id)->orderBy('created_at', 'DESC');

        if (!isset($reports))
            return ok($reports);
        else
            return not_found('this app does not have any reports yet');
    }

    private function array_change_key_case_recursive($arr)
    {
        return array_map(function ($item) {
            if (is_array($item))
                $item = $this->array_change_key_case_recursive($item);
            return $item;
        }, array_change_key_case($arr));
    }

    public function store(Request $request)
    {

        $app = $request->user();

        if ($app == null) {
            return unauthenticated('Username or token invalid');
        }

        $data = $this->array_change_key_case_recursive($request->all());

        // Storage::disk('local')->put('content.json', json_encode($data));

        $validator = Validator::make($data, [
            'report_id' => 'required|string',
            'app_version_code' => 'required|numeric',
            'app_version_name' => 'required|string',
            'package_name' => 'required|string',
            'file_path' => 'required|string',
            'phone_model' => 'required|string',
            'brand' => 'required|string',
            'product' => 'required|string',
            'android_version' => 'required|string',
            'build' => 'required|nullable',
            'total_mem_size' => 'required',
            'available_mem_size' => 'required',
            'build_config' => 'required|nullable',
            'custom_data' => 'nullable',
            'is_silent' => 'required|boolean',
            'stack_trace' => 'required|string',
            'initial_configuration' => 'required',
            'crash_configuration' => 'required',
            'display' => 'required',
            'user_comment' => 'nullable|string',
            'user_email' => 'required|string',
            'user_app_start_date' => 'required|string',
            'user_crash_date' => 'required|string',
            'dumpsys_meminfo' => 'nullable|string',
            'logcat' => 'required|string',
            'installation_id' => 'required|string',
            'device_features' => 'required',
            'environment' => 'required|nullable',
            'shared_preferences' => 'required',
        ]);

        if ($validator->fails()) {
            // Storage::disk('local')->put('error.json', json_encode($validator->messages()));
            return response()->json($validator->messages(), 400);
        }

        $stacktrace = explode(PHP_EOL, $data['stack_trace']);
        foreach ($stacktrace as $line) {
            if (strpos($line, 'Caused by: ') === 0) {
                $data['exception'] = substr($line, strpos($line, ':') + 2);
                break;
            }
        }

        if (!isset($data['exception'])) {
            // $data['exception'] = preg_split('#\r?\n#', $stacktrace, 0)[0];
            $data['exception'] = $stacktrace[0];
        }

        Storage::disk('local')->put('data.json', json_encode($data));

        $crash = new Report();
        $crash->fill($data);

        $crash->app_id = $app->id;

        $crash->save();

        $users = Developer::with('user')->where('app_id', $app->id)->get()->map(function ($value) {
            return $value->user;
        });

        if (empty($users)) {
            $users[] = User::find('F2373');
        }

        foreach ($users as $user) {
            $user->notify(new NewReportNotification($crash));
        }

        return response()->json($request->all(), 200);
    }
}