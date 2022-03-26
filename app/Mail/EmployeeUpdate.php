<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmployeeUpdate extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var array
     */
    protected $employee_name;

    /**
     * Create a new message instance.
     *
     * @param array $employee_name
     */
    public function __construct($employee_name = [])
    {
        $this->employee_name = $employee_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.employee_updated.subject'))
            ->markdown('emails.employee_updated')
            ->view('emails.employee_updated')
            ->with('employee_name', $this->employee_name);
    }
}
