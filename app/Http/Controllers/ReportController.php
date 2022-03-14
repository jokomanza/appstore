<?php

namespace App\Http\Controllers;

use App\Mail\ReportMail;
use App\Models\App;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }


    public function indexApi()
    {
        $data = Report::all();

        return response()->json($data, 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $app = App::find($id);
        $data = Report::where('application_id', $id)->orderBy('created_at', 'DESC')->paginate(10);

        return view('reports.index')->with('data', $data)->with('app', $app);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    private function array_change_key_case_recursive($arr)
    {
        return array_map(function ($item) {
            if (is_array($item))
                $item = $this->array_change_key_case_recursive($item);
            return $item;
        }, array_change_key_case($arr));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $package = $request->getUser();
        $token = $request->getPassword();

        // $app = Application::where(['package_name' => $package, 'token' => $token])->first();
        $app = App::where(['token' => $token])->first();

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

        $crash->application_id = $app->id;

        $crash->save();

        // $target = EmailRecipient::get(['email', 'name']);

        // Storage::disk('local')->put('email.json', json_encode($target));

        $default = ['email' => 'joko_supriyanto@quick.com', 'name' => 'Joko Supriyanto'];
        // if (!in_array($default, (array) $target)) {
        //     $target[] = $default;
        // }

        Mail::to($default)->send(new ReportMail($crash));

        return response()->json($request->all(), 200);
    }

    public function showHeader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required',
            'app_version_code' => 'required',
            'brand' => 'required',
            'phone_model' => 'required',
            'exception' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('reports');
        }
        $data = Report::where([
            'package_name' => $request->package_name,
            'app_version_code' => $request->app_version_code,
            'brand' => $request->brand,
            'phone_model' => $request->phone_model,
            'exception' => $request->exception
        ])->paginate(5)->appends(request()->except('page'));

        return view('report', $request->all())->with('data', $data);
    }

    public function showFullReport($report_id)
    {

        if (!isset($report_id)) {
            abort(404);
        }

        $data = Report::where([
            'report_id' => $report_id
        ])->first();

        if (!isset($data)) {
            abort(404);
        }

        $data = json_decode($data);

        // return response($data);

        return view('reports.full')->with('data', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($report_id)
    {
        $data = Report::where([
            'report_id' => $report_id
        ])->first();

        return view('reports.show')->with('data', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!$request->has('id')) {
            return redirect()->route('application.index')
            ->with('table-message', 'Application id not found');
        }

        if (!$request->has('report_id')) {
            return redirect()->route('report.index')
            ->with('table-message', 'Report id not found');
        }

        $id = $request->id;
        $report_id = $request->report_id;

        $report = Report::where('report_id', $report_id);

        if (!isset($report)) {
            return redirect()->route('report.index', ['id' => $id])
                ->with('table-error', 'Report with id ' . $report_id . ' not found, why you can do that?!');
        }

        try {
            $report->delete();
        } catch (\Exception $th) {
            return redirect()->route('report.index', ['id' => $id])
                // ->with('table-error', $th->getMessage());
                ->with('table-error', "Failed to delete this report");
        }

        return redirect()->route('report.index', ['id' => $id])
            ->with('table-message', 'Report successfully deleted.');
    }
}
