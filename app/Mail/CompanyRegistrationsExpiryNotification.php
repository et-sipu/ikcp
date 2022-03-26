<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyRegistrationsExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $expired;
    private $warning;

    public function __construct($expired = [],$warning = [])
    {
        //
        $this->expired = $expired;
        $this->warning = $warning;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.company_registration_expiry.subject'))
            //->markdown('emails.certificate_expiry')
            ->view('emails.company_registration_expiry')
            ->with('expired', $this->expired)
            ->with('warning', $this->warning);
    }
}
