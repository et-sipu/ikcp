<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\FlightReservationQuotation;
use App\Repositories\Contracts\FlightReservationQuotationRepository;

/**
 * Class EloquentFlightReservationQuotationRepository.
 */
class EloquentFlightReservationQuotationRepository extends EloquentBaseRepository implements FlightReservationQuotationRepository
{
    /**
     * EloquentFlightReservationQuotationRepository constructor.
     *
     * @param FlightReservationQuotation $flight_reservation_quotation
     */
    public function __construct(FlightReservationQuotation $flight_reservation_quotation)
    {
        parent::__construct($flight_reservation_quotation);
    }

//    /**
//     * @param $name
//     *
//     * @return FlightReservationQuotation
//     */
//    public function find($name)
//    {
//        /** @var FlightReservationQuotation $flight_reservation_quotation */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\FlightReservationQuotation
     */
    public function store(array $input)
    {
        /** @var FlightReservationQuotation $flight_reservation_quotation */
        $input['trip_item_id'] = $input['heading']['id'];
        $flight_reservation_quotation = $this->make(array_except($input, []));

//        if ($this->find($flight_reservation_quotation->name)) {
//            throw new GeneralException(__('exceptions.backend.flight_reservation_quotations.already_exist'));
//        }

        DB::transaction(function () use ($flight_reservation_quotation) {
            if (! $flight_reservation_quotation->save()) {
                throw new GeneralException(__('exceptions.backend.flight_reservation_quotations.create'));
            }

            return true;
        });

        return $flight_reservation_quotation;
    }

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     * @param array                      $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\FlightReservationQuotation
     */
    public function update(FlightReservationQuotation $flight_reservation_quotation, array $input)
    {
        $input['trip_item_id'] = $input['heading']['id'];
//        if (($existingFlightReservationQuotation = $this->find($flight_reservation_quotation->name))
//          && $existingFlightReservationQuotation->id !== $flight_reservation_quotation->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.flight_reservation_quotations.already_exist'));
//        }

        DB::transaction(function () use ($flight_reservation_quotation, $input) {
            if (! $flight_reservation_quotation->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.flight_reservation_quotations.update'));
            }

            return true;
        });

        return $flight_reservation_quotation;
    }

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(FlightReservationQuotation $flight_reservation_quotation)
    {
        if (! $flight_reservation_quotation->delete()) {
            throw new GeneralException(__('exceptions.backend.flight_reservation_quotations.delete'));
        }

        return true;
    }
}
