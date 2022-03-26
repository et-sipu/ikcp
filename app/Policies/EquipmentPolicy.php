<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EquipmentPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view equipment.
     *
     * @param User  $authenticatedUser
     * @param Equipment $equipment
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, Equipment $equipment)
    {
        if ($authenticatedUser->can('view equipment')) {
            return true;
        }

        if ($authenticatedUser->can('view own equipment')) {
            return $authenticatedUser->id === $equipment->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can edit equipment.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, Equipment $equipment)
    {
        if ($authenticatedUser->can('edit equipment')) {
            return true;
        }

        if ($authenticatedUser->can('view own equipment')) {
            return $authenticatedUser->id === $equipment->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can delete equipment.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, Equipment $equipment)
    {
        if ($authenticatedUser->can('delete equipment')) {
            return true;
        }

        if ($authenticatedUser->can('view own equipment')) {
            return $authenticatedUser->id === $equipment->vessel->piloted_by;
        }

        return false;
    }

}
