<?php

namespace App\Http\Controllers\Base;

use App\Admin;
use App\Models\App;
use App\Models\Permission;
use App\Models\Report;
use App\Notifications\NewReportNotification;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

abstract class HomeBaseController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return View
     */
    public function index()
    {
        $recentApps = App::orderBy('updated_at', 'DESC')->limit(5)->get();
        $appsCount = App::count();
        $usersCount = User::count();
        $adminsCount = Admin::count();
        $errorsCount = Report::count();
        $isClientDeveloper = Auth::user()->isDeveloperOf(Permission::whereHas('app', function ($q) {
            $q->where('package_name', 'com.quick.quickappstore');
        })->first());

        $reportNotifications = [];

        foreach(Auth::user()->unreadNotifications
            ->where('type', NewReportNotification::class)
            ->all() as $report) {

            $data = $report->data;

            $app = App::find($data['app_id']);

            $reportNotifications[] = [
                'message' => "Ada error report baru di aplikasi $app->name",
                'link' => route($this->getView() . '.notification.report.show', $report->id)
            ];
        }


        return view(
            $this->getView() . '.home',
            compact('recentApps', 'appsCount', 'usersCount', 'adminsCount', 'errorsCount', 'isClientDeveloper', 'reportNotifications')
        );
    }
}
