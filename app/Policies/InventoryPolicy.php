<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view inventory.
     *
     * @param User  $authenticatedUser
     * @param Inventory $inventory
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, Inventory $inventory)
    {
        if ($authenticatedUser->can('view inventories')) {
            return true;
        }

        if ($authenticatedUser->can('view own inventories')) {
            return $authenticatedUser->id === $inventory->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can edit inventory.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, Inventory $inventory)
    {
        if ($authenticatedUser->can('edit inventories')) {
            return true;
        }

        if ($authenticatedUser->can('edit own inventories')) {
            return $authenticatedUser->id === $inventory->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can delete inventory.
     *
     * @param User  $authenticatedUser
     * @param Leave $leave
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, Inventory $inventory)
    {
        if ($authenticatedUser->can('delete inventories')) {
            return true;
        }

        if ($authenticatedUser->can('delete own inventories')) {
            return $authenticatedUser->id === $inventory->vessel->piloted_by;
        }

        return false;
    }

}
