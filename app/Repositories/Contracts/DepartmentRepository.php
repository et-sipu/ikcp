<?php

namespace App\Repositories\Contracts;

use App\Models\Department;

/**
 * Interface DepartmentlRepository.
 */
interface DepartmentRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Department
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Department $department
     * @param array      $input
     *
     * @return mixed
     */
    public function update(Department $department, array $input);

    /**
     * @param Department $department
     *
     * @return mixed
     */
    public function destroy(Department $department);
}
