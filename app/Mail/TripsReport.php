<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TripsReport extends Mailable
{
    use Queueable, SerializesModels;
private $pdf;

    /**
     * Create a new message instance.
     *
     * @param array $pdf
     */
    public function __construct($pdf = [])
    {
        $this->pdf= $pdf;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $file = $this->pdf->merge('string', storage_path('report.pdf'));

        return $this->subject(__('mails.trips_report.subject'))
            ->markdown('emails.trips_report')
            ->attachData($file, 'report.pdf', [
                'mime' => 'application/pdf',
            ]);
//            ->attach('/path/to/file', [
//                'as' => 'name.pdf',
//                'mime' => 'application/pdf',
//            ]);
    }
}
