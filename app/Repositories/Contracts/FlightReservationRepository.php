<?php

namespace App\Repositories\Contracts;

use App\Models\FlightReservation;
use App\Models\Contracts\Workflowable;

/**
 * Interface FlightReservationlRepository.
 */
interface FlightReservationRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return FlightReservation
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param FlightReservation $flight_reservation
     * @param array             $input
     *
     * @return mixed
     */
    public function update(FlightReservation $flight_reservation, array $input);

    /**
     * @param FlightReservation $flight_reservation
     *
     * @return mixed
     */
    public function destroy(FlightReservation $flight_reservation);

    /**
     * @param Workflowable $flight_reservation
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Workflowable $flight_reservation, $new_status);

    /**
     * @param array $flight_reservations
     *
     * @return mixed
     */
    public function generatePRF(array $flight_reservations);

    /**
     * @param array $flight_reservations
     *
     * @return mixed
     */
    public function generateReport(array $flight_reservations);
}
