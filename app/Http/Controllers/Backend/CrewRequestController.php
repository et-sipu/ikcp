<?php

namespace App\Http\Controllers\Backend;

use App\Models\Crew;
use App\Models\CrewRequest;
use Spatie\Fractal\Fractal;
use Illuminate\Http\Request;
use App\Utils\RequestSearchQuery;
use Illuminate\Support\Facades\DB;
use App\Transformers\CrewRequestTransformer;
use App\Http\Requests\StoreCrewRequestRequest;
use App\Http\Requests\UpdateCrewRequestRequest;
use App\Repositories\Contracts\CrewRequestRepository;

class CrewRequestController extends BackendController
{
    /**
     * @var CrewRequestRepository
     */
    private $crew_requests;

    /**
     * CrewRequestController constructor.
     */
    public function __construct(CrewRequestRepository $crew_requests)
    {
        $this->crew_requests = $crew_requests;
    }

    /**
     * @param CrewRequest $crew_request
     *
     * @return \Spatie\Fractalistic\Fractal
     */
    public function show(CrewRequest $crew_request)
    {
        // $this->authorize('view crew_requests');

        return Fractal::create()->item($crew_request, new CrewRequestTransformer());
    }

    public function search(Request $request)
    {
        $query = $this->crew_requests->query()->with('vessel', 'replacement_of', 'suggested_seafarer', 'rank');

        $query->leftJoin('employees as e1', 'e1.id', '=', 'crew_requests.replacement_of_id');
        $query->leftJoin('employees as e2', 'e2.id', '=', 'crew_requests.suggested_seafarer_id');
        $query->leftJoin('ranks', 'ranks.id', '=', 'crew_requests.rank_id');
        $query->join('vessels', 'vessels.id', '=', 'crew_requests.vessel_id');
        $query->select([
            DB::raw('concat(e1.name,\' \',e1.surname) as replacement'),
            DB::raw('concat(e2.name,\' \',e2.surname) as suggested'),
            'vessels.name as vessel_name', 'ranks.name as rank_name', 'vessels.code as vessel_code', 'crew_requests.*', ]);

        $extraSearch = json_decode($request->get('extraSearch'));

        if (isset($extraSearch->statuses)) {
            $query = $query->whereIn('status', $extraSearch->statuses);
        }

        $requestSearchQuery = new RequestSearchQuery($request, $query, ['vessel.name', 'vessel.code', 'replacement_of.name-surname', 'suggested_seafarer.name-surname', 'rank.name']);

        return $requestSearchQuery->result(new CrewRequestTransformer());
    }

    /**
     * @param StoreCrewRequestRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function store(StoreCrewRequestRequest $request)
    {
        $this->authorize('create crew requests');

        $this->crew_requests->store($request->all());

        return $this->redirectResponse($request, __('alerts.backend.crew_requests.created'));
    }

    /**
     * @param CrewRequest              $crew_request
     * @param UpdateCrewRequestRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function update(CrewRequest $crew_request, UpdateCrewRequestRequest $request)
    {
        $this->authorize('edit crew requests');

        $this->crew_requests->update($crew_request, $request->all());

        return $this->redirectResponse($request, __('alerts.backend.crew_requests.updated'));
    }

    /**
     * @param CrewRequest $crew_request
     * @param Request     $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return mixed
     */
    public function destroy(CrewRequest $crew_request, Request $request)
    {
        $this->authorize('delete crew requests');

        $this->crew_requests->destroy($crew_request);

        return $this->redirectResponse($request, __('alerts.backend.crew_requests.deleted'));
    }

    /**
     * @param CrewRequest $crew_request
     * @param Request     $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function mark_as_done(CrewRequest $crew_request, Request $request)
    {
        $this->authorize('edit crew requests');

        $this->crew_requests->change_status($crew_request, 'done');

        return $this->redirectResponse($request, __('alerts.backend.crew_requests.updated'));
    }
}
