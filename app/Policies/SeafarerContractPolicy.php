<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SeafarerContract;
use Illuminate\Auth\Access\HandlesAuthorization;

class SeafarerContractPolicy
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
     * @param User             $authenticatedUser
     * @param SeafarerContract $contract
     *
     * @return bool
     */
    public function view(User $authenticatedUser, SeafarerContract $contract)
    {
        if ($authenticatedUser->can('view seafarer contracts')) {
            return true;
        }

        if ($authenticatedUser->can('view own seafarer contracts')) {
            return $authenticatedUser->id === $contract->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can add sign for seafarer.
     *
     * @param User             $authenticatedUser
     * @param SeafarerContract $contract
     *
     * @return bool
     */
    public function add_sign(User $authenticatedUser, SeafarerContract $contract)
    {
        if ($authenticatedUser->can('sign seafarer on and off')) {
            return true;
        }

        if ($authenticatedUser->can('sign own seafarer on and off')) {
            return $authenticatedUser->id === $contract->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can add sign for seafarer.
     *
     * @param User             $authenticatedUser
     * @param SeafarerContract $contract
     *
     * @return bool
     */
    public function delete_sign(User $authenticatedUser, SeafarerContract $contract)
    {
        if ($authenticatedUser->can('approve seafarer signs')) {
            return true;
        }

        if ($authenticatedUser->can('delete pending seafarer signs')) {
            return $authenticatedUser->id === $contract->vessel->piloted_by;
        }

        return false;
    }

    /**
     * Determine whether the user can update seafarer contract.
     *
     * @param User             $authenticatedUser
     * @param SeafarerContract $contract
     *
     * @return bool
     */
    public function update(User $authenticatedUser, SeafarerContract $contract)
    {
        if ($authenticatedUser->can('edit seafarer contracts') && \in_array($contract->status, [SeafarerContract::$pending, SeafarerContract::$approved], true)) {
            return true;
        }

        if ($authenticatedUser->can('edit own seafarer contracts') && $contract->status === SeafarerContract::$approved) {
            return $authenticatedUser->id === $contract->vessel->piloted_by;
        }

        return false;
    }
}
