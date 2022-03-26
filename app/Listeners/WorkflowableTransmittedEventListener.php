<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\ApproveRequired;
use App\Notifications\RequestProgressed;
use App\Events\WorkflowableTransmitted;
use Illuminate\Support\Facades\Notification;

class WorkflowableTransmittedEventListener
{
    /**
     * Handle the event.
     *
     * @param WorkflowableTransmitted $event
     */
    public function handle(WorkflowableTransmitted $event)
    {
        if ($event->workflowable->get_owner()) {
            $event->workflowable->get_owner()->notify(new RequestProgressed($event->workflowable));
            User::find(1)->notify(new RequestProgressed($event->workflowable));
//            Mail::to($event->workflowable->get_owner())
////            Mail::to('a.alkhoudary@inaikiara.com.my')
//                ->bcc('a.alkhoudary@inaikiara.com.my')
//                ->send(new WorkflowablePassed($event->workflowable));
        }

        if (\count($event->workflowable->get_receivers())) {
            $users = $event->workflowable->get_receivers();
//            array_push($users, User::find(1));
            $users->push(User::find(1));
            Notification::send($users, new ApproveRequired($event->workflowable));

//            Mail::to($event->workflowable->get_receivers())
//                ->bcc('a.alkhoudary@inaikiara.com.my')
//                ->send(new WorkflowableToPass($event->workflowable));
        }
    }
}
