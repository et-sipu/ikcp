<?php

namespace App\Mail;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    /**
     * @var Announcement|array
     */
    private $announcement;

    /**
     * Create a new message instance.
     *
     * @param Announcement $announcement
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailable =  $this->subject( $this->announcement->subject)
            ->view('emails.announcement')
            ->with('subject', $this->announcement->subject)
            ->with('content', $this->announcement->content)
            ;

        foreach ($this->announcement->attachments_info as $attachment) {
            $mailable->attach($attachment['path']);
        }

        return $mailable;
    }
}