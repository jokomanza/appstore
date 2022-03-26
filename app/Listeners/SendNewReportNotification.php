<?php

namespace App\Listeners;

use App\Admin;
use App\Notifications\NewReportNotification;
use Illuminate\Support\Facades\Notification;

class SendNewReportNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $users = Admin::get();

        Notification::send($users, new NewReportNotification($event->report));
    }
}
