<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;

class SeafarerContract extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var SeafarerContract
     */
    private $seafarerContract;

    /**
     * @param \App\Models\SeafarerContract $seafarerContract
     */
    public function __construct(\App\Models\SeafarerContract $seafarerContract)
    {
        $this->seafarerContract = $seafarerContract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = App::make('dompdf.wrapper');
        $this->seafarerContract->load([
            'seafarer.contactInfo',
            'seafarer.financialInfo',
            'seafarer.capabilitiesInfo',
            'seafarer.documents',
        ]);
        $pdf->loadView('templates.seafarer_contract', ['seafarerContract' => $this->seafarerContract]);

        return $this->subject(__('mails.seafarer_contract_singing_request.subject'))
            ->markdown('emails.seafarer_contract_signing_request')
            ->withContract($this->seafarerContract)
            ->withLink(config('app.url').'/Crew/seafarer_contracts/contracts/'.$this->seafarerContract->id.'/edit')
            ->attachData($pdf->stream(), preg_replace("/[^\w]+/", '_', $this->seafarerContract->seafarer->full_name).'.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
