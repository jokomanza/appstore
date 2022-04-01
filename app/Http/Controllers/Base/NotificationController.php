<?php

namespace App\Http\Controllers\Base;

use App\Models\App;
use App\Notifications\NewReportNotification;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

abstract class NotificationController extends BaseController
{
    /**
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request)
    {
        $reportNotifications = [];

        foreach (Auth::user()->unreadNotifications
                     ->where('type', NewReportNotification::class)
                     ->all() as $report) {

            $data = $report->data;
            $exception = $data['exception'];

            $application = App::find($data['application_id']);

            $reportNotifications[] = [
                'message' => "There is a new error report in the  $application->name applicationlication with a $exception exception, please check and fix it soon",
                'link' => route($this->getUserType() . '.notification.report.show', $report->id)
            ];
        }

        return view($this->getUserType() . '.notification.index', compact('reportNotifications'));
    }
}
