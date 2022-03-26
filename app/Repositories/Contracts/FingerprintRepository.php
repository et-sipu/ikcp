<?php

namespace App\Repositories\Contracts;

use App\Models\Employee;
use App\Models\Fingerprint;

/**
 * Interface FingerprintlRepository.
 */
interface FingerprintRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Fingerprint
//     */
//    public function find($name);

    /**
     * @param Employee $employee
     * @param array    $staff_ids
     *
     * @return mixed
     */
    public function create_attendance_records(Employee $employee, $staff_ids = []);
}
