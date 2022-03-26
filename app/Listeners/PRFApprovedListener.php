<?php

namespace App\Listeners;

use App\Models\PaymentRequisition;
use App\Events\WorkflowableTransmitted;
use App\Repositories\Contracts\FlightReservationRepository;

class PRFApprovedListener
{
    /**
     * @var PaymentRequisitionRepository
     */
    private $flight_reservations;

    /**
     * PRFApprovedListener constructor.
     *
     * @param FlightReservationRepository $flight_reservations
     */
    public function __construct(FlightReservationRepository $flight_reservations)
    {
        $this->flight_reservations = $flight_reservations;
    }

    /**
     * Handle the event.
     *
     * @param WorkflowableTransmitted $event
     */
    public function handle(WorkflowableTransmitted $event)
    {
//        if ($event->workflowable instanceof PaymentRequisition && $event->workflowable->flight_reservations && ('releasing' === $event->workflowable->status || 'rejected' === $event->workflowable->status)) {
//            $flight_reservations = $event->workflowable->flight_reservations;
//
//            $flight_reservations_rep = $this->flight_reservations;
//
//            $flight_reservations->each(function ($flight_reservation) use ($flight_reservations_rep, $event) {
//                $flight_reservations_rep->changeStatus($flight_reservation, 'releasing' === $event->workflowable->status ? 'prf_approved' : 'prf_rejected');
//            });
//        }
    }
}
