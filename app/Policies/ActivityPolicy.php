<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function add_comment(User $authenticatedUser, Activity $activity)
    {
        if ($authenticatedUser->can('view activities')) {
            return true;
        }

        if ($authenticatedUser->can('view own activities')) {
            return $authenticatedUser->id === $activity->by_department->hod_id || $authenticatedUser->id === $activity->from_department->hod_id;
        }

        return false;
    }

    public function mark_as_done(User $authenticatedUser, Activity $activity)
    {
        if ($authenticatedUser->can('view own activities')) {
            return $authenticatedUser->id === $activity->by_department->hod_id;
        }

        return false;
    }
}
