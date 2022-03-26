<?php

namespace App\Listeners;

use App\Models\User;
use App\Mail\EmployeeUpdate;
use App\Events\EmployeeUpdated;
use App\Notifications\EmployeeInfoUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EmployeeUpdatedListener
{
    /**
     * Create the event listener.
     */

    /**
     * Handle the event.
     *
     * @param EmployeeUpdated $event
     */
    public function handle(EmployeeUpdated $event)
    {
        if ($event->employee->is_seafarer) {
            return;
        }
        $role = 'HR HOD';

        $users = User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->get();

        $users->push(User::find(1));

        Notification::send($users, new EmployeeInfoUpdated($event->employee));
    }
}
