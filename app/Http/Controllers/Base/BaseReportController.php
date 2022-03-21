<?php

namespace App\Http\Controllers\Base;

use App\Models\App;
use App\Models\Report;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\View\View;

abstract class BaseReportController extends BaseController
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Factory|Application|View
     */
    public function show(Request $request, $packageName, $id)
    {
        $report = Report::with('app')->whereHas('app', function ($q) use ($packageName) {
            $q->where('package_name', $packageName);
        })->where('id', $id)->firstOrFail();

        return view($this->getView() . '.reports.show', compact('report' ));
    }
    
    public function showFull($reportId)
    {
        $report = Report::with('app')->where('report_id', $reportId)->firstOrFail();
        $report = json_decode($report);

        return view($this->getView() . '.reports.full', compact('report'));
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
            return redirect()->route($this->getView() . '.app.show', $packageName);
        }
        else return back()->withErrors('Failed to delete data');
    }


    public function getDataTables(Request $request, $packageName)
    {
        $app = App::where('package_name', $packageName)->first();

        if (!$app) return not_found();

        $columns = [
            0 => 'created_at',
            1 => 'app_version_name',
            2 => 'android_version',
            3 => 'device',
            4 => 'exception',
            5 => 'action',
        ];

        $totalData = Report::with('app')->whereHas('app', function ($q) use ($app) {
            $q->where('id', $app->id);
        })->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $reports = Report::with('app')->whereHas('app', function ($q) use ($app) {
                $q->where('id', $app->id);
            })->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $reports = Report::with('app')->whereHas('app', function ($q) use ($app) {
                $q->where('id', $app->id);
            })->where('app_version_name', 'LIKE', "%$search%")
                ->orWhere('android_version', 'LIKE', "%$search%")
                ->orWhere('brand', 'LIKE', "%$search%")
                ->orWhere('exception', 'LIKE', "%$search%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
            $totalFiltered = Report::with('app')->whereHas('app', function ($q) use ($app) {
                $q->where('id', $app->id);
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
}
