<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeavePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view leave.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, Leave $leave)
    {
        if ($authenticatedUser->can('view leaves')) {
            return true;
        }

        if ($authenticatedUser->can('view own leaves')) {
            return $leave->employee && $leave->employee->department &&
                (
                    $authenticatedUser->id === $leave->employee->user_id
                    || $authenticatedUser->id === $leave->employee->department->hod_id
                    || ($leave->employee->report_to && $authenticatedUser->id === $leave->employee->report_to->user->id)
                );
        }

        return false;
    }

    /**
     * Determine whether the user can edit leave.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, Leave $leave)
    {
//        if ($authenticatedUser->can('view leaves') && $authenticatedUser->can('pass hr_approving leaves') && 'hr_approving' === $leave->status) {
//            return true;
//        }

        if ($authenticatedUser->can('view own leaves')) {
//            return $leave->employee && $leave->employee->department && ($authenticatedUser->id === $leave->employee->user_id || $authenticatedUser->id === $leave->employee->department->hod_id) && 'hod_approving' === $leave->status;
            return $leave->employee && $leave->employee->department && ($authenticatedUser->id === $leave->employee->user_id) && 'hod_approving' === $leave->status;
        }

        return false;
    }

    /**
     * Determine whether the user can view leave.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, Leave $leave)
    {
        if ($authenticatedUser->can('delete leaves')) {
            return true;
        }

        if ($authenticatedUser->can('view own leaves')) {
//            return $leave->employee && $leave->employee->department && ($authenticatedUser->id === $leave->employee->user_id || $authenticatedUser->id === $leave->employee->department->hod_id) && 'hod_approving' === $leave->status;
            return $leave->employee && $leave->employee->department && ($authenticatedUser->id === $leave->employee->user_id) && 'hod_approving' === $leave->status;
        }

        return false;
    }

    /**
     * Determine whether the user can view leave.
     *
     * @param User   $authenticatedUser
     * @param Leave  $leave
     * @param string $transition
     *
     * @return mixed
     */
    public function pass(User $authenticatedUser, Leave $leave, $transition = '')
    {
        if ('hod_approved_long_leave' === $transition) {
            return false;
        }
        if ($authenticatedUser->can('pass '.$leave->status.' leaves')) {
            return true;
        }
        if ('hod_approving' === $leave->status) {
            return ($authenticatedUser->id === $leave->employee->department->hod_id && $leave->employee->user->id !== $leave->employee->department->hod_id)
                    ||
                    ($leave->employee->report_to && $authenticatedUser->id === $leave->employee->report_to->user->id && $leave->employee->user->id !== $leave->employee->report_to->user->id)
                ;
        }

        return false;
    }
}
