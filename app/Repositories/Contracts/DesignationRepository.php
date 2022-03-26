<?php

namespace App\Repositories\Contracts;

use App\Models\Designation;

/**
 * Interface DesignationlRepository.
 */
interface DesignationRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Designation
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Designation $designation
     * @param array       $input
     *
     * @return mixed
     */
    public function update(Designation $designation, array $input);

    /**
     * @param Designation $designation
     *
     * @return mixed
     */
    public function destroy(Designation $designation);
}
