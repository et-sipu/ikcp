<?php

namespace App\Transformers;

use App\Models\Department;
use League\Fractal\TransformerAbstract;

class DepartmentTransformer extends TransformerAbstract
{
    public function transform(Department $department)
    {
        return [
            'id'         => $department->id,
            'name'       => $department->name,
            'hod'        => ['id' => $department->hod_id, 'name' => $department->hod->name],
            'can_edit'   => $department->can_edit,
            'can_delete' => $department->can_delete,
        ];
    }
}
