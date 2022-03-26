<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VesselInsuranceExpiry extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $expired;
    private $warning;

    public function __construct($expired=[], $warning=[])
    {
        //
        $this->expired =$expired;
        $this->warning =$warning;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.vessel_insurance_expiry.subject'))
        ->view('emails.vessel_insurance_expiry')
            /**
             * ->with([
             * ['expired', $this->expired],
             * ['warning', $this->warning],
             */
        ->with('expired', $this->expired)
        ->with('warning', $this->warning);
    }
}
