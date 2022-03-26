<?php

namespace App\Listeners;

use App\Mail\SeafarerContract;
use Illuminate\Support\Facades\Mail;
use App\Events\SeafarerContractStatusChanged;

class SeafarerContractStatusChangedEventListener
{
    /**
     * Handle the event.
     */
    public function handle(SeafarerContractStatusChanged $event)
    {
        if ($event->new_status === \App\Models\SeafarerContract::$approved && $event->seafarerContract->vessel && $event->seafarerContract->vessel->pilot) {
            Mail::to('a.alkhoudary@inaikiara.com.my')
                ->cc(['a.alkhoudary@inaikiara.com.my', 'jessie@inaikiara.com.my', 'crewing@inaikiara.com.my'])
                ->send(new SeafarerContract($event->seafarerContract));
        }
    }
}
