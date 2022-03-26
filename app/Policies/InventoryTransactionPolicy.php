<?php

namespace App\Policies;

use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryTransactionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view inventory transaction.
     *
     * @param User  $authenticatedUser
     * @param InventoryTransaction $inventory_transaction
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, InventoryTransaction $inventory_transaction)
    {
        if ($authenticatedUser->can('view inventory transactions')) {
            return true;
        }

        if ($authenticatedUser->can('view own inventory transactions')) {
            return $authenticatedUser->id === $inventory_transaction->inventory->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can edit inventory transaction.
     *
     * @param User  $authenticatedUser
     * @param InventoryTransaction $inventory_transaction
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, InventoryTransaction $inventory_transaction)
    {
        if ($authenticatedUser->can('edit inventory transactions')) {
            return true;
        }

        if ($authenticatedUser->can('edit own inventory transactions')) {
            return $authenticatedUser->id === $inventory_transaction->inventory->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can delete inventory transaction.
     *
     * @param User  $authenticatedUser
     * @param InventoryTransaction $inventory_transaction
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, InventoryTransaction $inventory_transaction)
    {
        if ($authenticatedUser->can('delete inventory transactions')) {
            return true;
        }

        if ($authenticatedUser->can('delete own inventory transactions')) {
            return $authenticatedUser->id === $inventory_transaction->inventory->vessel->piloted_by;
        }

        return false;
    }

}
