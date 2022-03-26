<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vessel;
use Illuminate\Auth\Access\HandlesAuthorization;

class VesselPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view seafarer contract.
     *
     * @param User   $authenticatedUser
     * @param Vessel $vessel
     *
     * @return bool
     */
    public function view(User $authenticatedUser, Vessel $vessel)
    {
        if ($authenticatedUser->can('view vessels') || $authenticatedUser->can('view vessel certificates') || $authenticatedUser->can('view vessel forms')) {
            return true;
        }

        if ($authenticatedUser->can('view own vessels')) {
            return $authenticatedUser->id === $vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can view seafarer contract.
     *
     * @param User   $authenticatedUser
     * @param Vessel $vessel
     *
     * @return bool
     */
    public function edit(User $authenticatedUser, Vessel $vessel)
    {
        if ($authenticatedUser->can('edit vessels') || $authenticatedUser->can('view vessel certificates') || $authenticatedUser->can('view vessel forms')) {
            return true;
        }

        if ($authenticatedUser->can('edit own vessels')) {
            return $authenticatedUser->id === $vessel->piloted_by;
        }

        return false;
    }
}
