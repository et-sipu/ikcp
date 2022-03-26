<?php

namespace App\Repositories\Contracts;

use App\Models\VesselForm;

/**
 * Interface VesselFormlRepository.
 */
interface VesselFormRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return VesselForm
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param VesselForm $vessel_form
     * @param array       $input
     *
     * @return mixed
     */
    public function update(VesselForm $vessel_form, array $input);

    /**
     * @param VesselForm $vessel_form
     *
     * @return mixed
     */
    public function destroy(VesselForm $vessel_form);
}
