<?php

namespace App\Repositories\Contracts;

use App\Models\FlightReservationQuotation;

/**
 * Interface FlightReservationQuotationlRepository.
 */
interface FlightReservationQuotationRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return FlightReservationQuotation
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     * @param array                      $input
     *
     * @return mixed
     */
    public function update(FlightReservationQuotation $flight_reservation_quotation, array $input);

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     *
     * @return mixed
     */
    public function destroy(FlightReservationQuotation $flight_reservation_quotation);
}
