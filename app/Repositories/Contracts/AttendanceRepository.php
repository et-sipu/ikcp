<?php

namespace App\Repositories\Contracts;

use App\Models\Attendance;

/**
 * Interface AttendancelRepository.
 */
interface AttendanceRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Attendance
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Attendance $attendance
     * @param array      $input
     *
     * @return mixed
     */
    public function update(Attendance $attendance, array $input);

    /**
     * @param Attendance $attendance
     *
     * @return mixed
     */
    public function destroy(Attendance $attendance);

    /**
     * @return mixed
     */
    public function get_dept_movement();

    /**
     * @param array $attendances_array
     *
     * @return mixed
     */
    public function save_dept_movement(array $attendances_array);

    public function represent_store(array $attendances_array);
}
