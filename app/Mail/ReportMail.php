<?php

namespace App\Mail;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    private $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Storage::disk('local')->put('report.json', json_encode($this->report));
        return $this->view('reports.mail')->with('report', $this->report);
    }
}
