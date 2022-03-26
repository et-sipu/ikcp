<?php

namespace App\Transformers;

use App\Models\Leave;
use League\Fractal\TransformerAbstract;

class LeaveTransformer extends TransformerAbstract
{
    public function transform(Leave $leave)
    {
        $transitions = [];
        foreach ($leave->workflow_transitions() as $transition) {
            if ('hod_approved_long_leave' !== $transition->getName()) {
                array_push($transitions, $transition->getName());
            }
        }

        return [
            'id'                                => $leave->id,
            'requester_id'                      => $leave->requester_id,
            'employee_id'                       => $leave->requester_id,
            'requester_name'                    => $leave->employee ? $leave->employee->full_name : '',
            'department_name'                   => $leave->employee && $leave->employee->department ? $leave->employee->department->name : '',
            'employee_id'                       => $leave->employee_id,
            'leave_type'                        => ['id' => $leave->leave_type, 'name' => __('labels.backend.leaves.leave_types.'.$leave->leave_type)],
            'start_date'                        => $leave->start_date,
            'end_date'                          => $leave->end_date,
            'days_count'                        => $leave->days_count,
            'period'                            => $leave->period,
            'reason'                            => $leave->reason,
            'status'                            => $leave->status,
            'attachments'                       => $leave->attachments_info,
            'available_transitions'             => $transitions,
            'can_edit'                          => $leave->can_edit,
            'can_pass'                          => $leave->can_pass,
            'can_view'                          => $leave->can_view,
            'can_delete'                        => $leave->can_delete,
        ];
    }
}
