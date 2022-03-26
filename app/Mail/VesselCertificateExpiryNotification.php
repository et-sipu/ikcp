<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VesselCertificateExpiryNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $local_warnings;
    /**
     * @var array
     */
    private $local_critical;
    private $local_expired;
    private $vessel_name;
    /**
     * @var array
     */
    private $logo;

    /**
     * CertificateExpiry constructor.
     *
     * @param array  $local_warnings
     * @param array  $local_critical
     * @param array  $local_expired
     * @param string $vessel_name
     */
    public function __construct($local_warnings = [], $local_critical = [], $local_expired = [], $vessel_name = '', $logo = [])
    {
        $this->local_warnings = $local_warnings;
        $this->local_critical = $local_critical;
        $this->local_expired = $local_expired;
        $this->vessel_name = $vessel_name;
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
            ->view('emails.vessel_expiry_certificate_notification')
            ->with('local_warnings', $this->local_warnings)
            ->with('local_critical', $this->local_critical)
            ->with('local_expired', $this->local_expired)
            ->with('vessel_name', $this->vessel_name)
            ->with('logo', $this->logo);
    }
}
