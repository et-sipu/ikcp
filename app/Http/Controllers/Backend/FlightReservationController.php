<?php

namespace App\Http\Controllers\Backend;

use App\Mail\TripsReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Models\FlightReservation;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Builder;
use App\Transformers\FlightReservationTransformer;
use App\Http\Requests\StoreFlightReservationRequest;
use App\Http\Requests\UpdateFlightReservationRequest;
use App\Repositories\Contracts\FlightReservationRepository;


class FlightReservationController extends BackendController
{
    /**
     * @var FlightReservationRepository
     */
    protected $flight_reservations;

    protected $repo_name = 'flight_reservations';

    /**
     * Create a new controller instance.
     *
     * @param FlightReservationRepository $flight_reservations
     */
    public function __construct(FlightReservationRepository $flight_reservations)
    {
        $this->flight_reservations = $flight_reservations;
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
        $query = $this->extractQuery($request);

        $requestSearchQuery = new RequestSearchQuery($request, $query, []);

        return $requestSearchQuery->result(new FlightReservationTransformer());
    }

    /**
     * @param Request $request
     *
     * @return Builder
     */
    public function extractQuery(Request $request): Builder
    {
        $query = $this->flight_reservations->query()->with(['prf', 'media', 'employee.department', 'trip_item', 'vessel']);
        $query->JoinWithDepartment();

        if (! Gate::check('view flight reservations')) {
//            $query->MyRequisitions(auth()->user()->id);
            $query->MyRecords(auth()->user()->employee->id, auth()->user()->id);
        }
        $query->select([DB::raw('users.name as requester_name'), DB::raw('departments.name as department_name'), 'flight_reservations.*']);

        $extraSearch = \is_array($request->get('extraSearch')) ? json_decode(json_encode($request->get('extraSearch'))) : json_decode($request->get('extraSearch'));

        if (isset($extraSearch->departments) && \is_array($extraSearch->departments) && \count($extraSearch->departments) > 0) {
            $department_ids = array_pluck($extraSearch->departments, 'id');
            $query->InOneOfDepartments($department_ids);
        }

        if (isset($extraSearch->date) && $extraSearch->date) {
            $dates = explode(' to ', $extraSearch->date);
            if (\count($dates) > 1) {
                $query->where(function (Builder $q) use ($dates) {
                    $q->whereDate('flight_reservations.created_at', '>=', $dates[0])->whereDate('flight_reservations.created_at', '<=', $dates[1]);
                });
            } else {
                $query->whereDate('flight_reservations.created_at', '=', $dates);
            }
        }


        if (isset($extraSearch->requesters) && \is_array($extraSearch->requesters) && \count($extraSearch->requesters) > 0) {
            $requester_ids = array_pluck($extraSearch->requesters, 'id');

            $query->whereIn('requester_id', $requester_ids);
        }

        if (isset($extraSearch->status_selected) && \is_array($extraSearch->status_selected) && \count($extraSearch->status_selected) > 0) {
            $status = array_pluck($extraSearch->status_selected, 'id');

            $query->whereIn('status', $status);
        }

        if (isset($extraSearch->form_target) && strlen($extraSearch->form_target) > 0) {
            $query->where(function($query) use($extraSearch){
                $query->where(function($query) use($extraSearch){
                    $query->where('form_target','like', '%'.$extraSearch->form_target.'%')->where('form_type','SITE');
                })->orWhere(function($query) use($extraSearch) {
                    $query->where('form_type', 'VESSEL')->whereHas('vessel',function($query) use($extraSearch){
                        $query->where('name','like','%'.$extraSearch->form_target.'%');
                    });
                });
            });
        }

        if (isset($extraSearch->passenger_name) && '' !== $extraSearch->passenger_name) {
            $passenger_name = $extraSearch->passenger_name;
            $query->whereHas('media', function ($q) use ($passenger_name) {
                $q->where('collection_name', 'passengers')->whereRaw("lower(custom_properties) like '%\"name\":\"%".mb_strtolower($passenger_name)."%\",\"designation%'");
            });
        }

        return $query;
    }

    /**
     * @param FlightReservation $flight_reservation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(FlightReservation $flight_reservation)
    {
        $this->authorize('view', $flight_reservation);

        return Fractal::create()->item($flight_reservation, new FlightReservationTransformer());
    }

    /**
     * @param StoreFlightReservationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreFlightReservationRequest $request)
    {
        $this->authorize('create flight reservations');

        $this->flight_reservations->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.created'));
    }

    /**
     * @param FlightReservation              $flight_reservation
     * @param UpdateFlightReservationRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(FlightReservation $flight_reservation, UpdateFlightReservationRequest $request)
    {
        $this->authorize('edit', $flight_reservation);

        $this->flight_reservations->update($flight_reservation, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.updated'));
    }

    /**
     * @param FlightReservation $flight_reservation
     * @param Request           $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(FlightReservation $flight_reservation, Request $request)
    {
        $this->authorize('delete', $flight_reservation);

        $this->flight_reservations->destroy($flight_reservation);

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.deleted'));
    }

    /**
     * @param Request           $request
     * @param FlightReservation $flight_reservation
     * @param $status
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, FlightReservation $flight_reservation, $status)
    {
        $this->authorize('pass', $flight_reservation);

        $this->flight_reservations->changeStatus($flight_reservation, $status);

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.status_changed'));
    }

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return Config::get('workflow.flight_reservation_process.places');
    }

    public function getHappyPath()
    {
        return Config::get('workflow.flight_reservation_process.happy_path');
    }

    /**
     * @param Request           $request
     * @param FlightReservation $flight_reservation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws GeneralException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function generatePRF(Request $request, FlightReservation $flight_reservation)
    {
        $this->authorize('pass', [$flight_reservation, true]);

//        if($flight_reservation->prf){
//            throw new GeneralException(__('exceptions.backend.flight_reservations.prf_already_generated'));
//        }

        $this->flight_reservations->generatePRF([$flight_reservation->id]);

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.prf_generated'));
    }

    /**
     * @param Request           $request
     * @param FlightReservation $flight_reservation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws GeneralException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function bulkGeneratePRF(Request $request)
    {
        $this->authorize('pass prf_issuing flight reservations');

        $this->flight_reservations->generatePRF($request->get('ids'));

        return $this->redirectResponse($request, __('alerts.backend.flight_reservations.prf_generated'));
    }

    /**
     * @param Request           $request
     * @param FlightReservation $flight_reservation
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws GeneralException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function bulkGenerateReport(Request $request)
    {
        $this->authorize('generate flight reservations report');

        $pdf = $this->flight_reservations->generateReport($request->get('ids'));

        $pdf->merge('browser', 'report.pdf');
//        return $pdf->merge('browser', 'report.pdf');
    }

    public function mo_report()
    {
        $mytime = Carbon::today()->addDays(1);
        $last_month= Carbon::today()->subMonth();
        $ids=[];
        $flights =FlightReservation::whereBetween('created_at', [$last_month, $mytime])->get();
        $ids= $flights->pluck('id')->toArray();
        $pdf = $this->flight_reservations->generateReport($ids);

        Mail::to(['a.alkhoudary@inaikiara.com.my'])
            ->send(new TripsReport($pdf));

    }

    /**
     * @param FlightReservation $flight_reservation
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getFlightItems(FlightReservation $flight_reservation)
    {
        $items = $flight_reservation->trip_item()->where('trip_type', '=', 'FLIGHT')->get();

        return $items->map(function ($item) {
            return ['id' => $item->id, 'name' => $item['trip_attributes']['departure_from'].' -> '.$item['trip_attributes']['departure_to']];
        });
    }
}
