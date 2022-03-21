<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Admin\Controller;
use App\Mail\ReportMail;
use App\Models\App;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class ReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
    public function index()
    {
        return view('reports.index');
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return not_found();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $packageName, $id)
    {
        $report = Report::with('app')->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->where('id', $id)->firstOrFail();

        // dd(url()->previous() == route('client.index'));

        return view('reports.show', compact('report', 'prevUrl'));
    }
    
    public function showFull($reportId)
    {
        $report = Report::where('report_id', $reportId)->firstOrFail();
        $report = json_decode($report);

        return view('reports.full', compact('report'));
    }

    public function destroy(Request $request, $packageName, $id)
    {
        if (Auth::user()->access_level == 1) throw new UnauthorizedException();
        
        $app = App::where('package_name', $packageName)->firstOrFail();

        if (!isset($app)) {
            return back()->withErrors('application not found');
        }

        $route = $request->get('redirect', 'report.index');

        $version = Report::where(['app_id' => $app->id, 'id' => $id])->firstOrFail();

        if ($version->delete()) {
            return redirect()->route($route);
        }
        else return back()->withErrors('Failed to delete data');
    }


    public function getDataTables(Request $request)
    {
        $access_level = Auth::user()->access_level;
        $current_reg_num = Auth::user()->registration_number;

        $columns = [
            0 => 'created_at',
            1 => 'app_version_name',
            2 => 'android_version',
            3 => 'device',
            4 => 'exception',
            5 => 'action',
        ];

        // if ($access_level == 1) {
        //     $totalData = Report::with(['app'])->whereHas('app', function ($q) use ($value) {
        //         $q->with('developers')->where('', $value['package_name']);
        //     })->count();
        // } else {
        // }
        $totalData = Report::with('app')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $reports = Report::with('app')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        }
        else {
            $search = $request->input('search.value');

            $reports = Report::with('app')->whereHas('app', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->where('app_version_name', 'LIKE', "%$search%")
                ->orWhere('android_version', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('exception', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Report::with('app')->whereHas('app', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%");
            })->where('app_version_name', 'LIKE', "%$search%")
                ->orWhere('android_version', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('exception', 'LIKE', "%$search%")
                ->count();
        }
        $data = array();
        if (!empty($reports)) {
            foreach ($reports as $report) {
                $edit = route('report.show', [$report->app->package_name, $report->id]);
                $nestedData['date'] = $report->created_at->diffForHumans();
                $nestedData['application'] = $report->app->name;
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
}
