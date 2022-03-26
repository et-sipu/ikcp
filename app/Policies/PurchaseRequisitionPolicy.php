<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseRequisitionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param $ability
     *
     * @return bool
     */
    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * @param User                $authenticatedUser
     * @param PurchaseRequisition $purchaseRequisition
     *
     * @return bool
     */
    public function view(User $authenticatedUser, PurchaseRequisition $purchaseRequisition)
    {
        if ($authenticatedUser->can('view purchase requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own purchase requisitions')) {
            return $authenticatedUser->id === $purchaseRequisition->requester_id || $authenticatedUser->id === $purchaseRequisition->requester->department->hod_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view purchase requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, PurchaseRequisition $purchaseRequisition)
    {
        if (
            $authenticatedUser->can('view purchase requisitions') &&
            $authenticatedUser->can('pass finance_approving purchase requisitions') &&
            'finance_approving' === $purchaseRequisition->status
        ) {
            return true;
        }

        if (
            $authenticatedUser->can('view purchase requisitions') &&
            $authenticatedUser->can('pass dec_approving purchase requisitions') &&
            'dec_approving' === $purchaseRequisition->status
        ) {
            return true;
        }

        if (
            $authenticatedUser->can('view purchase requisitions') &&
            $authenticatedUser->can('pass releasing purchase requisitions') &&
            'releasing' === $purchaseRequisition->status
        ) {
            return true;
        }

        if ($authenticatedUser->can('view own purchase requisitions')) {
            return ($authenticatedUser->id === $purchaseRequisition->requester_id || $authenticatedUser->id === $purchaseRequisition->requester->department->hod_id) && $purchaseRequisition::$initial_state === $purchaseRequisition->status;
        }

        return false;
    }

    /**
     * Determine whether the user can delete purchase requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, PurchaseRequisition $purchaseRequisition)
    {
        if ($authenticatedUser->can('delete purchase requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own purchase requisitions')) {
            return ($authenticatedUser->id === $purchaseRequisition->requester_id || $authenticatedUser->id === $purchaseRequisition->requester->department->hod_id) && $purchaseRequisition::$initial_state === $purchaseRequisition->status;
        }

        return false;
    }

    /**
     * Determine whether the user can pass purchase requisition from specific status.
     *
     * @param User                $authenticatedUser
     * @param PurchaseRequisition $purchaseRequisition
     * @param bool                $with_urgent_check
     *
     * @return bool
     */
    public function pass(User $authenticatedUser, PurchaseRequisition $purchaseRequisition, $with_urgent_check = false)
    {
        if ($authenticatedUser->can('pass '.$purchaseRequisition->status.' purchase requisitions')) {
            return true;
        }

        if ($with_urgent_check && $authenticatedUser->can('pass purchase requisitions urgently')) {
            return true;
        }

//        if ($purchaseRequisition::$initial_state === $purchaseRequisition->status) {
//            return $authenticatedUser->id === $purchaseRequisition->requester->department->hod_id;
//        }

        return false;
    }

    /**
     * Determine whether the user can print purchase requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function print(User $authenticatedUser, PurchaseRequisition $purchaseRequisition)
    {
        if (! ($purchaseRequisition->reached_minimum_print_state() && ! \in_array($purchaseRequisition->status, ['rejected', 'delivered'], true))) {
            return false;
        }

        if ($authenticatedUser->can('print purchase requisitions') && $authenticatedUser->can('view purchase requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('print purchase requisitions') && $authenticatedUser->can('view own purchase requisitions')) {
            return $authenticatedUser->id === $purchaseRequisition->requester_id || $authenticatedUser->id === $purchaseRequisition->requester->department->hod_id;
        }

        return false;
    }
}
