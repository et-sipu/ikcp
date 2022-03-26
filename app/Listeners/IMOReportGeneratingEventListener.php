<?php

namespace App\Listeners;

use App\Mail\IMOReport;
use App\Events\IMOReportGenerated;
use Illuminate\Support\Facades\Mail;

class IMOReportGeneratingEventListener
{
    /**
     * Handle the event.
     */
    public function handle(IMOReportGenerated $event)
    {
        Mail::to($event->vessel->pilot->email)
            ->cc(['a.alkhoudary@inaikiara.com.my', 'jessie@inaikiara.com.my', 'maltazzam@inaikiara.com.my', 'crewing@inaikiara.com.my'])
            ->send(new IMOReport($event->vessel, $event->media));
    }
}
