<?php

namespace App\Repositories\Contracts;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\Contracts\Workflowable;

/**
 * Interface LeavelRepository.
 */
interface LeaveRepository extends BaseRepository
{
    /**
     * @param $employee_id
     * @param $start_date
     * @param $end_date
     *
     * @return Leave
     */
    public function find($employee_id, $start_date, $end_date);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Leave $leave
     * @param array $input
     *
     * @return mixed
     */
    public function update(Leave $leave, array $input);

    /**
     * @param Leave $leave
     *
     * @return mixed
     */
    public function destroy(Leave $leave);

    /**
     * @param Workflowable $leave
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Workflowable $leave, $new_status);

    /**
     * @param Employee|null $employee
     * @return mixed
     */
    public function employee_leave_entitlements(Employee $employee = null);
}
