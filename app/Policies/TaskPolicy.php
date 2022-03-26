<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
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
    public function add_comment(User $authenticatedUser, Task $task)
    {
        if ($authenticatedUser->can('view tasks')) {
            return true;
        }

        if ($authenticatedUser->can('view own tasks')) {
            return $authenticatedUser->id === $task->assigned_department->hod_id;
        }

        return false;
    }

    public function mark_as_done(User $authenticatedUser, Task $task)
    {
        if ($authenticatedUser->can('view own tasks')) {
            return $authenticatedUser->id === $task->assigned_department->hod_id;
        }

        return false;
    }
}
