<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\App;
use App\Notifications\NewReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

abstract class NotificationController extends BaseController
{
    public function index(Request $request)
    {
        $reportNotifications = [];

        foreach(Auth::user()->unreadNotifications
                    ->where('type', NewReportNotification::class)
                    ->all() as $report) {

            $data = $report->data;
            $exception = $data['exception'];

            $app = App::find($data['app_id']);

            $reportNotifications[] = [
                'message' => "There is a new error report in the  $app->name application with a $exception exception, please check and fix it soon",
                'link' => route($this->getUserType() . '.notification.report.show', $report->id)
            ];
        }

        return view($this->getUserType() . '.notification.index', compact('reportNotifications'));
    }
}
