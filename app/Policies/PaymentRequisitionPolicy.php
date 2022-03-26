<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PaymentRequisition;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentRequisitionPolicy
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
     * @param User               $authenticatedUser
     * @param PaymentRequisition $paymentRequisition
     *
     * @return bool
     */
    public function view(User $authenticatedUser, PaymentRequisition $paymentRequisition)
    {
        if ($authenticatedUser->can('view payment requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own payment requisitions')) {
            return $authenticatedUser->id === $paymentRequisition->employee->user_id || $authenticatedUser->id === $paymentRequisition->employee->department->hod_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view payment requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, PaymentRequisition $paymentRequisition)
    {
        if (
            $authenticatedUser->can('view payment requisitions') &&
            $authenticatedUser->can('pass finance_approving payment requisitions') &&
            'finance_approving' === $paymentRequisition->status
        ) {
            return true;
        }

        if (
            $authenticatedUser->can('view payment requisitions') &&
            $authenticatedUser->can('pass dec_approving payment requisitions') &&
            'dec_approving' === $paymentRequisition->status
        ) {
            return true;
        }

        if (
            $authenticatedUser->can('view payment requisitions') &&
            $authenticatedUser->can('pass releasing payment requisitions') &&
            'releasing' === $paymentRequisition->status
        ) {
            return true;
        }

        if ($authenticatedUser->can('view own payment requisitions')) {
            return ($authenticatedUser->id === $paymentRequisition->employee->user_id || $authenticatedUser->id === $paymentRequisition->employee->department->hod_id) && 'hod_approving' === $paymentRequisition->status;
        }

        return false;
    }

    /**
     * Determine whether the user can delete payment requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, PaymentRequisition $paymentRequisition)
    {
        if ($authenticatedUser->can('delete payment requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('view own payment requisitions')) {
            return ($authenticatedUser->id === $paymentRequisition->employee->user_id || $authenticatedUser->id === $paymentRequisition->employee->department->hod_id) && 'hod_approving' === $paymentRequisition->status;
        }

        return false;
    }

    /**
     * Determine whether the user can pass payment requisition from specific status.
     *
     * @param User               $authenticatedUser
     * @param PaymentRequisition $paymentRequisition
     * @param bool               $with_urgent_check
     *
     * @return bool
     */
    public function pass(User $authenticatedUser, PaymentRequisition $paymentRequisition, $with_urgent_check = false)
    {
        if ($authenticatedUser->can('pass '.$paymentRequisition->status.' payment requisitions')) {
            return true;
        }

        if ($with_urgent_check && $authenticatedUser->can('pass payment requisitions urgently')) {
            return true;
        }

        if ('hod_approving' === $paymentRequisition->status) {
            return $authenticatedUser->id === $paymentRequisition->employee->department->hod_id;
        }

        return false;
    }

    /**
     * Determine whether the user can print payment requisition.
     *
     * @param User $authenticatedUser
     * @param User $user
     *
     * @return mixed
     */
    public function print(User $authenticatedUser, PaymentRequisition $paymentRequisition)
    {
        if (! ($paymentRequisition->reached_minimum_print_state() && ! \in_array($paymentRequisition->status, ['rejected', 'closed'], true))) {
            return false;
        }

        if ($authenticatedUser->can('print payment requisitions') && $authenticatedUser->can('view payment requisitions')) {
            return true;
        }

        if ($authenticatedUser->can('print payment requisitions') && $authenticatedUser->can('view own payment requisitions')) {
            return $authenticatedUser->id === $paymentRequisition->employee->user_id || $authenticatedUser->id === $paymentRequisition->employee->department->hod_id;
        }

        return false;
    }
}
