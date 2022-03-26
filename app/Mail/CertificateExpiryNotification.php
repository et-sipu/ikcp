<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $warnings;
    /**
     * @var array
     */
    private $critical;
    private $expired;
    /**
     * @var array
     */
    private $logo;

    /**
     * CertificateExpiry constructor.
     *
     * @param array $warnings
     * @param array $critical
     * @param array $expired
     * @param array $logo
     */
    public function __construct($warnings = [], $critical = [], $expired = [], $logo = [])
    {
        $this->warnings = $warnings;
        $this->critical = $critical;
        $this->expired = $expired;
        $this->logo = $logo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.certificate_expiry.subject'))
            //->markdown('emails.certificate_expiry')
            ->view('emails.expiry_certificate_notification')
            ->with('warnings', $this->warnings)
            ->with('critical', $this->critical)
            ->with('expired', $this->expired)
            ->with('logo', $this->logo);
    }
}
