<?php

namespace App\Repositories\Contracts;

use App\Models\Employee;

/**
 * Interface EmployeelRepository.
 */
interface EmployeeRepository extends BaseRepository
{
    /**
     * @param $name
     *
     * @return Employee
     */
    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Employee $employee
     * @param array    $input
     *
     * @return mixed
     */
    public function update(Employee $employee, array $input);

    /**
     * @param Employee $employee
     *
     * @return mixed
     */
    public function destroy(Employee $employee);

    /**
     * @return mixed
     */
    public function get_employees_emails();
}
