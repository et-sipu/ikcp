<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Fingerprint;
use App\Repositories\Contracts\FingerprintRepository;

/**
 * Class EloquentFingerprintRepository.
 */
class EloquentFingerprintRepository extends EloquentBaseRepository implements FingerprintRepository
{
    /**
     * EloquentFingerprintRepository constructor.
     *
     * @param Fingerprint $fingerprint
     */
    public function __construct(Fingerprint $fingerprint)
    {
        parent::__construct($fingerprint);
    }

//    /**
//     * @param $name
//     *
//     * @return Fingerprint
//     */
//    public function find($name)
//    {
//        /** @var Fingerprint $fingerprint */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param Employee $employee
     * @param array    $staff_ids
     *
     * @return mixed|void
     */
    public function create_attendance_records(Employee $employee, $staff_ids = [])
    {
        $employee->fingerprints()->whereIn('fingerprints.staff_id', $staff_ids)->each(function (Fingerprint $fingerprint) use ($employee) {
            Attendance::firstOrCreate([
                'employee_id'     => $employee->id,
                'attendance_date' => $fingerprint->clocking->format('Y-m-d'),
            ], [
                'dept_movement' => 'P',
                'hr_review'     => 1,
            ]);
        });
    }
}
