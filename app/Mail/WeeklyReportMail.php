<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ReportLog;

class WeeklyReportMail extends Mailable
{

    use Queueable, SerializesModels;
    public $reportLog;

    public function __construct(ReportLog $reportLog)
    {
        $this->reportLog = $reportLog;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reporte Semanal Disponible')
                    ->view('mails.reporte_pdf')
                    ->with([
                        'reportUrl' => route('report.show', ['id' => $this->reportLog->id])
                    ]);
    }
}


