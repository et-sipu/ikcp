<?php

namespace App\Listeners;

use App\Events\AnnouncementPublished;
use App\Mail\AnnouncementMail;
use App\Models\Employee;
use App\Models\Group;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class AnnouncementPublishedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AnnouncementPublished $event
     * @return void
     */
    public function handle(AnnouncementPublished $event)
    {
        $receivers =[];

        $announcement = $event->announcement;

        if($announcement->destination['destination_type'] == 'DEPARTMENT'){
            foreach ($announcement->destination['to']  as $department){
                $department_id = $department['id'];
                $department = Department::where('id', '=',$department_id)->first();
                $users = $department->employees()->with('user')->whereHas('jobInfo', function ($query){$query->whereIn('employment_status', ['on_probation' , 'permanent']);})
                    ->get()->pluck('user')->filter(function ($user){return $user != null && $user->active == 1;});
                $receivers = array_merge($receivers, $users);
            }
        }
        elseif($announcement->destination['destination_type'] == 'LOCATION'){
            foreach ($announcement->destination['to']  as $branch){
                $branch_id = $branch['id'];
                $branch = Branch::where('id', '=',$branch_id)->first();
                $users= $branch->employees()->with('user')->whereHas('jobInfo', function ($query){$query->whereIn('employment_status', ['on_probation' , 'permanent']);})
                    ->get()->pluck('user')->filter(function ($user){return $user != null && $user->active == 1;});

                $receivers = array_merge($receivers, $users);
            }
        }
        elseif($announcement->destination['destination_type'] == 'GROUPS'){
            foreach ($announcement->destination['to']  as $group){
                $group_id = $group['id'];
                $group = Group::where('id', '=',$group_id)->first();
                $users= $group->users;
                $receivers = array_merge($receivers, $users);
            }
        }
        elseif($announcement->destination['destination_type'] == 'USERS'){
            $receivers = User::whereIn('email',$announcement->destination['to'])->get();
        }
        elseif($announcement->destination['destination_type'] == 'ALL'){
            $receivers = Employee::with('user')->whereHas('jobInfo', function ($query){$query->whereIn('employment_status', ['on_probation' , 'permanent']);})
                ->get()->pluck('user')->filter(function ($user){return $user != null && $user->active == 1;});
        }

        $cc = is_array($announcement->destination['cc']) ? $announcement->destination['cc'] : [];

        Notification::send($receivers, new \App\Notifications\AnnouncementPublished($announcement, $cc));
    }
}
