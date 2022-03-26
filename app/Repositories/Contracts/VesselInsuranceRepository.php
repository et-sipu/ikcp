<?php

namespace App\Repositories\Contracts;

use App\Models\VesselInsurance;

/**
 * Interface VesselInsurancelRepository.
 */
interface VesselInsuranceRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return VesselInsurance
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param VesselInsurance $vessel_insurance
     * @param array       $input
     *
     * @return mixed
     */
    public function update(VesselInsurance $vessel_insurance, array $input);

    /**
     * @param VesselInsurance $vessel_insurance
     *
     * @return mixed
     */
    public function destroy(VesselInsurance $vessel_insurance);
}
