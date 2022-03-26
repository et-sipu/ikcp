<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FlightReservation;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlightReservationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can view flight reservation.
     *
     * @param User              $authenticatedUser
     * @param FlightReservation $flightReservation
     *
     * @return mixed
     */
    public function view(User $authenticatedUser, FlightReservation $flightReservation)
    {
        if ($authenticatedUser->can('view flight reservations')) {
            return true;
        }

        if ($authenticatedUser->can('view own flight reservations')) {
//            return $authenticatedUser->id === $flightReservation->requester_id || $authenticatedUser->id === $flightReservation->requester->department->hod_id;
            return $flightReservation->employee && $flightReservation->employee->department && ($authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id);
        }

        return false;
    }

    /**
     * Determine whether the user can view flight reservation.
     *
     * @param User              $authenticatedUser
     * @param FlightReservation $flightReservation
     *
     * @return mixed
     */
    public function edit(User $authenticatedUser, FlightReservation $flightReservation)
    {
        if ($authenticatedUser->can('view flight reservations') && $authenticatedUser->can('pass purchasing flight reservations') && 'purchasing' === $flightReservation->status) {
            return true;
        }

        if ($authenticatedUser->can('view own flight reservations') && preg_match('/hod_approving/', $flightReservation->status)) {
            return $flightReservation->employee && $flightReservation->employee->department && ($authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id);
        }

//        if ($authenticatedUser->can('view flight reservations') && $authenticatedUser->can('pass deducting flight reservations') && 'deducting' === $flightReservation->status) {
//            return true;
//        }
//
//        if ($authenticatedUser->can('view flight reservations') && $authenticatedUser->can('pass budgeting flight reservations') && 'budgeting' === $flightReservation->status) {
//            return true;
//        }

//        if ($authenticatedUser->can('view own flight reservations')) {
////            return ($authenticatedUser->id === $flightReservation->requester_id || $authenticatedUser->id === $flightReservation->requester->department->hod_id) && ('hod_approving' === $flightReservation->status);
////            return $flightReservation->employee && $flightReservation->employee->department && ($authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id) && ('hod_approving' === $flightReservation->status);
//            return $flightReservation->employee && $flightReservation->employee->department && ($authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id) && preg_match('/hod_approving/', $flightReservation->status) ;
//        }

        return false;
    }

    /**
     * Determine whether the user can view flight reservation.
     *
     * @param User              $authenticatedUser
     * @param FlightReservation $flightReservation
     *
     * @return mixed
     */
    public function delete(User $authenticatedUser, FlightReservation $flightReservation)
    {
        if ($authenticatedUser->can('delete flight reservations')) {
            return true;
        }

//        if ($authenticatedUser->can('view own flight reservations')) {
        if ($authenticatedUser->can('view flight reservations') && preg_match('/hod_approving/', $flightReservation->status)) {
//            return ($authenticatedUser->id === $flightReservation->requester_id || $authenticatedUser->id === $flightReservation->requester->department->hod_id) && 'hod_approving' === $flightReservation->status;
            return $flightReservation->employee && $flightReservation->employee->department && ($authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id);
        }

        return false;
    }

    /**
     * Determine whether the user can view flight reservation.
     *
     * @param User              $authenticatedUser
     * @param FlightReservation $flightReservation
     * @param bool              $with_urgent_check
     *
     * @return mixed
     */
    public function pass(User $authenticatedUser, FlightReservation $flightReservation)//, $with_urgent_check = false)
    {
        if ($authenticatedUser->can('pass '.$flightReservation->status.' flight reservations')) {
            return true;
        }

//        if ($with_urgent_check && $authenticatedUser->can('pass flight reservations urgently')) {
//            return true;
//        }

//        if (preg_match('/hod_approving/', $flightReservation->status)) {
////            return $authenticatedUser->id === $flightReservation->requester->department->hod_id;
//            return $authenticatedUser->id === $flightReservation->employee->department->hod_id;
//        }

//        if ('proofing' === $flightReservation->status) {
//            return $authenticatedUser->id === $flightReservation->requester_id;
//        }

        return false;
    }

    /**
     * @param User              $authenticatedUser
     * @param FlightReservation $flightReservation
     *
     * @return bool
     */
    public function print(User $authenticatedUser, FlightReservation $flightReservation)
    {
        return true;
        if (! ($flightReservation->reached_minimum_print_state() && ! \in_array($flightReservation->status, ['rejected', 'closed'], true))) {
            return false;
        }

        if ($authenticatedUser->can('print flight reservations') && $authenticatedUser->can('view flight reservations')) {
            return true;
        }

        if ($authenticatedUser->can('print flight reservations') && $authenticatedUser->can('view own flight reservations')) {
//            return $authenticatedUser->id === $flightReservation->requester_id || $authenticatedUser->id === $flightReservation->requester->department->hod_id;
            return $authenticatedUser->id === $flightReservation->employee->user_id || $authenticatedUser->id === $flightReservation->employee->department->hod_id;
        }

        return false;
    }
}
