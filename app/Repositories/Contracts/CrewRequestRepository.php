<?php

namespace App\Repositories\Contracts;

use App\Models\CrewRequest;

/**
 * Interface VesselRepository.
 */
interface CrewRequestRepository extends BaseRepository
{
    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param CrewRequest $crewRequest
     * @param array       $input
     *
     * @return mixed
     */
    public function update(CrewRequest $crewRequest, array $input);

    /**
     * @param CrewRequest $crewRequest
     *
     * @return mixed
     */
    public function destroy(CrewRequest $crewRequest);

    /**
     * @param CrewRequest $crewRequest
     * @param $new_status
     *
     * @return mixed
     */
    public function change_status(CrewRequest $crewRequest, $new_status);
}
