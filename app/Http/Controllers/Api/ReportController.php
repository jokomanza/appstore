<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Report;
use App\Notifications\NewReportNotification;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:report-api');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $application = $request->user();

        $reports = Report::where('application_id', $application->id)->orderBy('created_at', 'DESC');

        if (!isset($reports)) return ok($reports);
        else return not_found('this app does not have any reports yet');
    }

    /**
     * Store a new report
     *
     * @param Request $request
     * @return JsonResponse
     * @throws MassAssignmentException
     */
    public function store(Request $request)
    {

        $application = $request->user();

        if ($application == null) return unauthenticated('app token invalid');

        $data = $this->array_change_key_case_recursive($request->all());

        // Storage::disk('local')->put('content.json', json_encode($data));

        $validator = Validator::make($data, [
            'report_id' => 'required|string',
            'application_version_code' => 'required|numeric',
            'application_version_name' => 'required|string',
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
            'user_application_start_date' => 'required|string',
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

        // Debugging purpose
        // Storage::disk('local')->put('data.json', json_encode($data));

        $crash = new Report();
        $crash->fill($data);

        $crash->application_id = $application->id;

        $crash->save();

        $people = Permission::with('user')->where('application_id', $application->id)->get()->map(function ($value) {
            return $value->user;
        });

        if ($people->isEmpty()) {
            foreach (Admin::all() as $admin) $people[] = $admin;
        }

        Notification::send($people, new NewReportNotification($crash));

        return ok($request->all());
    }

    /**
     * Private helper function
     *
     * @param $arr
     * @return array|array[]
     */
    private function array_change_key_case_recursive($arr)
    {
        return array_map(function ($item) {
            if (is_array($item)) $item = $this->array_change_key_case_recursive($item);
            return $item;
        }, array_change_key_case($arr));
    }
}