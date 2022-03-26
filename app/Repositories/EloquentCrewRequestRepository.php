<?php

namespace App\Repositories;

use App\Models\CrewRequest;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\CrewRequestRepository;

/**
 * Class EloquentCrewRequestRepository.
 */
class EloquentCrewRequestRepository extends EloquentBaseRepository implements CrewRequestRepository
{
    /**
     * EloquentCrewRequestRepository constructor.
     *
     * @param CrewRequest $crewRequest
     */
    public function __construct(CrewRequest $crewRequest)
    {
        parent::__construct($crewRequest);
    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(array $input)
    {
        $input['vessel_id'] = $input['vessel']['id'];
        $input['replacement_of_id'] = $input['replaced_seafarer']['id'];
        $input['suggested_seafarer_id'] = $input['candidate_seafarer']['id'];
        $input['rank_id'] = $input['rank']['id'];

        $crew_request = $this->make($input);

        DB::transaction(function () use ($crew_request) {
            if (! $crew_request->save()) {
                throw new GeneralException(__('exceptions.backend.crew_requests.create'));
            }

            if ($crew_request->from_request) {
            }
        });

        return $crew_request;
    }

    /**
     * @param CrewRequest $crewRequest
     * @param array       $input
     *
     * @throws GeneralException
     *
     * @return CrewRequest
     */
    public function update(CrewRequest $crewRequest, array $input)
    {
        $input['replacement_of_id'] = $input['replaced_seafarer']['id'];
        $input['suggested_seafarer_id'] = $input['candidate_seafarer']['id'];
        $input['rank_id'] = $input['rank']['id'];

        if (! $crewRequest->update($input)) {
            throw new GeneralException(__('exceptions.backend.crew_requests.update'));
        }

        return $crewRequest;
    }

    /**
     * @param CrewRequest $crewRequest
     *
     * @throws GeneralException
     *
     * @return bool|null
     */
    public function destroy(CrewRequest $crewRequest)
    {
        if (! $crewRequest->delete()) {
            throw new GeneralException(__('exceptions.backend.crew_requests.delete'));
        }

        return true;
    }

    /**
     * @param CrewRequest $crewRequest
     * @param $new_status
     *
     * @throws GeneralException
     *
     * @return CrewRequest
     */
    public function change_status(CrewRequest $crewRequest, $new_status)
    {
        if (! $crewRequest->update(['status' => $new_status])) {
            throw new GeneralException(__('exceptions.backend.crew_requests.update'));
        }

        return $crewRequest;
    }
}
