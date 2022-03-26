<?php

namespace App\Repositories\Contracts;

use App\Models\Branch;
use App\Models\AttendanceRepresentative;

/**
 * Interface BranchlRepository.
 */
interface AttendanceRepresentativeRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Branch
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    public function update(AttendanceRepresentative $represent, array $input);

    /*
     * @param Branch $branch
     * @param array       $input
     *
     * @return mixed
     */

    /*
     * @param Branch $branch
     *
     * @return mixed
     */
}
