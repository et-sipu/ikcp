<?php

namespace App\Transformers;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use League\Fractal\TransformerAbstract;

class AttendanceTransformer extends TransformerAbstract
{
    public function transform(Attendance $attendance)
    {
        $fingerprints = array_values($attendance->fingerprints()->toArray());
//        $fingerprints = [['clocking' => '2019-01-01 09:32:00'],['clocking' => '2019-01-01 11:32:00'],['clocking' => '2019-01-01 13:32:00'],['clocking' => '2019-01-01 15:32:00'],['clocking' => '2019-01-01 17:32:00'],
//            ['clocking' => '2019-01-01 18:32:00'],['clocking' => '2019-01-01 19:32:00']];
        $first_fingerprint = $fingerprints ? Carbon::createFromFormat('Y-m-d H:i:s', $fingerprints[0]['clocking'])->format('H:i') : null;
        $last_fingerprint = $fingerprints ? Carbon::createFromFormat('Y-m-d H:i:s', end($fingerprints)['clocking'])->format('H:i') : null;
        $new_fingerprints = [];
        foreach ($fingerprints as $key => $fingerprint) {
            $new_fingerprints['thumb'.($key + 1)] = Carbon::createFromFormat('Y-m-d H:i:s', $fingerprint['clocking'])->format('H:i');
        }

        return [
            'id'                  => $attendance->id,
            'employee_name'       => ucwords($attendance->employee->full_name),
            'attendance_date'     => $attendance->attendance_date,
            'dept_movement'       => $attendance->dept_movement,
            'thumbs'              => [$new_fingerprints],
            'first_thumb'         => $first_fingerprint,
            'last_thumb'          => $last_fingerprint,
            'logged_in_time'      => $attendance->logged_in_time ? Carbon::createFromFormat('H:i:s', $attendance->logged_in_time)->format('H:i') : null,
            'logged_out_time'     => $attendance->logged_out_time ? Carbon::createFromFormat('H:i:s', $attendance->logged_out_time)->format('H:i') : null,
            'hr_review'           => $attendance->hr_review,
            'ey_review'           => $attendance->ey_review,
            'remarks'             => $attendance->remarks,
            'editable'            => false,
            'can_edit'            => $attendance->can_edit,
            'can_delete'          => $attendance->can_delete,
            '_showDetails'        => false,
        ];
    }
}
