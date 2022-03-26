<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CashRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class CashRequisitionPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view cash requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, CashRequisition $cashRequisition)
    {
        if ($authenticatedUser->can('view cash requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own cash requisitions')) {
//            return $authenticatedUser->id === $cashRequisition->requester_id || $authenticatedUser->id === $cashRequisition->requester->department->hod_id;
            return $authenticatedUser->id === $cashRequisition->employee->user_id || $authenticatedUser->id === $cashRequisition->employee->department->hod_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view cash requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, CashRequisition $cashRequisition)
    {
        if ($authenticatedUser->can('view cash requisitions') && $authenticatedUser->can('pass finance_approving cash requisitions') && 'finance_approving' === $cashRequisition->status) {
            return true;
        }

        if ($authenticatedUser->can('view cash requisitions') && $authenticatedUser->can('pass releasing cash requisitions') && 'releasing' === $cashRequisition->status) {
            return true;
        }

        if ($authenticatedUser->can('view own cash requisitions')) {
//            return ($authenticatedUser->id === $cashRequisition->requester_id || $authenticatedUser->id === $cashRequisition->requester->department->hod_id) && ('hod_approving' === $cashRequisition->status || 'proofing' === $cashRequisition->status);
            return ($authenticatedUser->id === $cashRequisition->employee->user_id || $authenticatedUser->id === $cashRequisition->employee->department->hod_id) && ('hod_approving' === $cashRequisition->status || 'proofing' === $cashRequisition->status);
        }

        return false;
    }

    /**
     * Determine whether the user can view cash requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, CashRequisition $cashRequisition)
    {
        if ($authenticatedUser->can('delete cash requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own cash requisitions')) {
//            return ($authenticatedUser->id === $cashRequisition->requester_id || $authenticatedUser->id === $cashRequisition->requester->department->hod_id) && 'hod_approving' === $cashRequisition->status;
            return ($authenticatedUser->id === $cashRequisition->employee->user_id || $authenticatedUser->id === $cashRequisition->employee->department->hod_id) && 'hod_approving' === $cashRequisition->status;
        }

        return false;
    }

    /**
     * Determine whether the user can view cash requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function pass(User $authenticatedUser, CashRequisition $cashRequisition, $with_urgent_check = false)
    {
        if ($authenticatedUser->can('pass '.$cashRequisition->status.' cash requisitions')) {
            return true;
        }

        if ($with_urgent_check && $authenticatedUser->can('pass cash requisitions urgently')) {
            return true;
        }

        if ('hod_approving' === $cashRequisition->status) {
//            return $authenticatedUser->id === $cashRequisition->requester->department->hod_id;
            return $authenticatedUser->id === $cashRequisition->employee->department->hod_id;
        }

        if ('proofing' === $cashRequisition->status) {
//            return $authenticatedUser->id === $cashRequisition->requester_id;
            return $authenticatedUser->id === $cashRequisition->employee->user_id;
        }

        return false;
    }

    /**
     * @param User            $authenticatedUser
     * @param CashRequisition $cashRequisition
     *
     * @return bool
     */
    public function print(User $authenticatedUser, CashRequisition $cashRequisition)
    {
        if (! ($cashRequisition->reached_minimum_print_state() && ! \in_array($cashRequisition->status, ['rejected', 'closed'], true))) {
            return false;
        }

        if ($authenticatedUser->can('print cash requisitions') && $authenticatedUser->can('view cash requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('print cash requisitions') && $authenticatedUser->can('view own cash requisitions')) {
//            return $authenticatedUser->id === $cashRequisition->requester_id || $authenticatedUser->id === $cashRequisition->requester->department->hod_id;
            return $authenticatedUser->id === $cashRequisition->employee->user_id || $authenticatedUser->id === $cashRequisition->employee->department->hod_id;
        }

        return false;
    }
}
