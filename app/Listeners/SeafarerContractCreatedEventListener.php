<?php

namespace App\Listeners;

use App\Mail\SeafarerContract;
use Illuminate\Support\Facades\Mail;
use App\Events\SeafarerContractCreated;

class SeafarerContractCreatedEventListener
{
    /**
     * Handle the event.
     */
    public function handle(SeafarerContractCreated $event)
    {
        Mail::to($event->seafarerContract->vessel->pilot->email)
            ->cc(['a.alkhoudary@inaikiara.com.my', 'jessie@inaikiara.com.my', 'crewing@inaikiara.com.my'])
            ->send(new SeafarerContract($event->seafarerContract));
    }
}
