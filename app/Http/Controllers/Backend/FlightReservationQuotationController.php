<?php

namespace App\Http\Controllers\Backend;

use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Database\Eloquent\Builder;
use App\Models\FlightReservationQuotation;
use App\Transformers\FlightReservationQuotationTransformer;
use App\Http\Requests\StoreFlightReservationQuotationRequest;
use App\Http\Requests\UpdateFlightReservationQuotationRequest;
use App\Repositories\Contracts\FlightReservationQuotationRepository;

class FlightReservationQuotationController extends BackendController
{
    /**
     * @var FlightReservationQuotationRepository
     */
    protected $flight_reservation_quotations;

    /**
     * Create a new controller instance.
     *
     * @param FlightReservationQuotationRepository $flight_reservation_quotations
     */
    public function __construct(FlightReservationQuotationRepository $flight_reservation_quotations)
    {
        $this->flight_reservation_quotations = $flight_reservation_quotations;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return array
     */
    public function search(Request $request)
    {
        $query = $this->flight_reservation_quotations->query()->with([]);

        $extraSearch = json_decode($request->get('extraSearch'));
//        dd($extraSearch);
        if (isset($extraSearch->flight_reservation_id) && is_numeric($extraSearch->flight_reservation_id)) {
//            $query = $query->where('flight_reservation_id', $extraSearch->flight_reservation_id);
            $flight_reservation_id = $extraSearch->flight_reservation_id;
            $query = $query->whereHas('trip_item', function (Builder $q) use ($flight_reservation_id) {
                return $q->where('flight_reservation_id', $flight_reservation_id)->where('trip_type', '=', 'FLIGHT');
            });
        }
        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new FlightReservationQuotationTransformer());
    }

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(FlightReservationQuotation $flight_reservation_quotation)
    {
        // $this->authorize('view flight reservation quotations');

        return Fractal::create()->item($flight_reservation_quotation, new FlightReservationQuotationTransformer());
    }

    /**
     * @param StoreFlightReservationQuotationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreFlightReservationQuotationRequest $request)
    {
        // $this->authorize('create flight reservation quotations');

        $this->flight_reservation_quotations->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.flight_reservation_quotations.created'));
    }

    /**
     * @param FlightReservationQuotation              $flight_reservation_quotation
     * @param UpdateFlightReservationQuotationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(FlightReservationQuotation $flight_reservation_quotation, UpdateFlightReservationQuotationRequest $request)
    {
        // $this->authorize('edit flight reservation quotations');

        $this->flight_reservation_quotations->update($flight_reservation_quotation, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.flight_reservation_quotations.updated'));
    }

    /**
     * @param FlightReservationQuotation $flight_reservation_quotation
     * @param Request                    $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(FlightReservationQuotation $flight_reservation_quotation, Request $request)
    {
        // $this->authorize('delete flight reservation quotations');

        $this->flight_reservation_quotations->destroy($flight_reservation_quotation);

        return $this->redirectResponse($request, __('alerts.backend.flight_reservation_quotations.deleted'));
    }
}
