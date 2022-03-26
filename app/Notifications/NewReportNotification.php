<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Report;

class NewReportNotification extends Notification
{
    use Queueable;

    private $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {

        return (new MailMessage)
            ->greeting(sprintf('Hello %s!', $notifiable->name))
            ->line(sprintf('Your application %s (version %s) %s %s, with exception %s', $this->report->package_name, $this->report->app_version_name, $this->report->is_silent ? 'has encountered an error on' : 'crashed at', $this->report->created_at->toDateTimeString(), $this->report->exception))
            ->action('View on Quick App Store', route('user.report.show', [$this->report->app->package_name, $this->report->id]))
            ->line('Please check immediately and fix the problem');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'report' => $this->report
        ];
    }
}
