<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\AttendanceRepository;

/**
 * Class EloquentAttendanceRepository.
 */
class EloquentAttendanceRepository extends EloquentBaseRepository implements AttendanceRepository
{
    /**
     * EloquentAttendanceRepository constructor.
     *
     * @param Attendance $attendance
     */
    public function __construct(Attendance $attendance)
    {
        parent::__construct($attendance);
    }

//    /**
//     * @param $name
//     *
//     * @return Attendance
//     */
//    public function find($name)
//    {
//        /** @var Attendance $attendance */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Attendance
     */
    public function store(array $input)
    {
        /** @var Attendance $attendance */
        $attendance = $this->make(array_except($input, []));

//        if ($this->find($attendance->name)) {
//            throw new GeneralException(__('exceptions.backend.attendances.already_exist'));
//        }

        DB::transaction(function () use ($attendance) {
            if (! $attendance->save()) {
                throw new GeneralException(__('exceptions.backend.attendances.create'));
            }

            return true;
        });

        return $attendance;
    }

    /**
     * @param Attendance $attendance
     * @param array      $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Attendance
     */
    public function update(Attendance $attendance, array $input)
    {
//        if (($existingAttendance = $this->find($attendance->name))
//          && $existingAttendance->id !== $attendance->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.attendances.already_exist'));
//        }

        DB::transaction(function () use ($attendance, $input) {
            if (! $attendance->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.attendances.update'));
            }

            return true;
        });

        return $attendance;
    }

    /**
     * @param Attendance $attendance
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Attendance $attendance)
    {
        if (! $attendance->delete()) {
            throw new GeneralException(__('exceptions.backend.attendances.delete'));
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function get_dept_movement()
    {
        $user = auth::User();
        if (! $user->represent) {
            return [];
        }
        $departments = $user->represent->departments;
        $branch_id = $user->represent->branch_id;

        $employees = Employee::InOneOfDepartments($departments)->InOneOfBranches($branch_id)->get();
        $attendances = [];

        $employees->each(function ($employee) use (&$attendances) {
            $attendance = $employee->attendance()->where('attendance_date', date('Y-m-d'))->first();
            array_push($attendances, [
                'employee_id'     => $employee->id,
                'user_name'       => ucwords($employee->full_name),
                'attendance_date' => date('Y-m-d'),
                'id'              => $attendance ? $attendance->id : null,
                'dept_movement'   => $attendance ? $attendance->dept_movement : null,
                'remarks'         => $attendance ? $attendance->remarks : null,
            ]);
        });

        return $attendances;
    }

    /**
     * @param array $attendances_array
     *
     * @throws \Throwable
     *
     * @return mixed
     */
    public function save_dept_movement(array $attendances_array)
    {
        DB::transaction(function () use ($attendances_array) {
            foreach ($attendances_array as $attendance) {
                if (! $attendance['id']) {
                    if ($attendance['dept_movement']) {
                        $this->store(array_except($attendance, ['user_name', 'id']));
                    }
                } else {
                    $this->update($this->query()->find($attendance['id']), array_except($attendance, ['user_name', 'id']));
                }
            }

            return true;
        });

        return true;
    }

    public function represent_store(array $input)
    {
        /** @var Attendance $attendance */
        $represent = $this->make(array_except($input, []));

        if ($this->find($represent->name)) {
            throw new GeneralException(__('exceptions.backend.attendances.already_exist'));
        }

        DB::transaction(function () use ($attendance) {
            if (! $attendance->save()) {
                throw new GeneralException(__('exceptions.backend.attendances.create'));
            }

            return true;
        });

        return $attendance;
    }
}
