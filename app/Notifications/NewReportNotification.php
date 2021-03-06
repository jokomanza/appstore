<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    private $userType;

    /**
     * @var Report
     */
    private $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userType, Report $report)
    {
        $this->userType = $userType;
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (setting('send_email_notification') == 'true') {
            return ['mail', 'database'];
        }
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->greeting(sprintf('Hello %s!', $notifiable->name))
            ->line(sprintf('Your application %s (version %s) %s %s, with exception %s', $this->report->package_name, $this->report->app_version_name, $this->report->is_silent ? 'has encountered an error on' : 'crashed at', $this->report->created_at->toDateTimeString(), $this->report->exception))
            ->action('View on Quick App Store', route($this->userType . '.report.show', [$this->report->app->package_name, $this->report->id]))
            ->line('Please check immediately and fix the problem');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'app_id' => $this->report->app_id,
            'report_id' => $this->report->id,
            'exception' => $this->report->exception,
            'created_at' => $this->report->created_at
        ];
    }
}
