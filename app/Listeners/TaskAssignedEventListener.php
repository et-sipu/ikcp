<?php

namespace App\Listeners;

use App\Mail\Task;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\Mail;

class TaskAssignedEventListener
{
    /**
     * Handle the event.
     */
    public function handle(TaskAssigned $event)
    {
        Mail::to($event->task->assigned_department->hod->email)
//        Mail::to('a.alkhoudary@inaikiara.com.my')
            ->cc([
                'a.alkhoudary@inaikiara.com.my',
                'jessie@inaikiara.com.my',
                'rohana@rimbaramin.com.my',
                'sara@inaikiara.com.my',
                'aslinda@inaikiara.com.my',
                'kamrul@inaikiara.com.my',
                'zaini@inaikiara.com.my',
                'khairul@inaikiara.com.my',
                'noriha@inaikiara.com.my',
                'mazly@inaikiara.com.my',
            ])
            ->send(new Task($event->task));
    }
}
