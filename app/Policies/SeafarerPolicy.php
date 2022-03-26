<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Seafarer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeafarerPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can update seafarer contract.
     *
     * @param User     $authenticatedUser
     * @param Seafarer $seafarer
     *
     * @return bool
     */
    public function update(User $authenticatedUser, Seafarer $seafarer)
    {
        if ($authenticatedUser->can('edit seafarers')) {
            return true;
        }

        if ($authenticatedUser->can('edit own seafarers') && ! $seafarer->ongoing_contract) {
            return true;
        } elseif ($authenticatedUser->can('edit own seafarers')) {
            return $authenticatedUser->id === $seafarer->ongoing_contract->vessel->piloted_by;
        }

        return false;
    }
}
