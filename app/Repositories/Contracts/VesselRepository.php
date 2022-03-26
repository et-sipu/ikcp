<?php

namespace App\Repositories\Contracts;

use App\Models\Vessel;

/**
 * Interface VesselRepository.
 */
interface VesselRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Vessel
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Vessel $vessel
     * @param array  $input
     *
     * @return mixed
     */
    public function update(Vessel $vessel, array $input);

    /**
     * @param Vessel $vessel
     *
     * @return mixed
     */
    public function destroy(Vessel $vessel);

    /**
     * @param Vessel $vessel
     * @param array  $input
     *
     * @return mixed
     */
    public function process_imo_report(Vessel $vessel, array $input);
}
