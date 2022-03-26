<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\AttendanceRepresentative;

class RepresentTransformer extends TransformerAbstract
{
    /**
     * A     Fractal transformer.
     *
     * @return array
     */
    public function transform(AttendanceRepresentative $represent)
    {
        return [
            'id'                    => $represent->id,
            'user_id'               => $represent->user_id,
            'user_name'             => $represent->assigned_user->name,
            'departments'           => $represent->assigned_department()->pluck('name', 'id'),
            'department_name'       => $represent->assigned_department()->pluck('name'),
            'branch_id'             => $represent->branch_id,
            'branch_name'           => $represent->assigned_branch->name,
        ];
    }
}
