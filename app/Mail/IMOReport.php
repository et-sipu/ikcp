<?php

namespace App\Mail;

use App\Models\Vessel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Queue\SerializesModels;

class IMOReport extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var Vessel
     */
    private $vessel;
    /**
     * @var Media
     */
    private $imo;

    /**
     * SeafarerContract constructor.
     *
     * @param Vessel $vessel
     * @param Media  $imo
     */
    public function __construct(Vessel $vessel, Media $imo)
    {
        $this->vessel = $vessel;
        $this->imo = $imo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.imo_report.subject', ['vessel' => $this->vessel->name]))
            ->markdown('emails.imo_report')
            ->withVessel($this->vessel)
            ->attach($this->imo->getPath(), [
                'mime' => 'application/pdf',
            ]);
    }
}
