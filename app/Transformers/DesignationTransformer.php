<?php

namespace App\Transformers;

use App\Models\Designation;
use League\Fractal\TransformerAbstract;

class DesignationTransformer extends TransformerAbstract
{
    public function transform(Designation $designation)
    {
        return [
            'id'                => $designation->id,
            'title'             => $designation->title,
            'department_id'     => $designation->department_id,
            'department_name'   => $designation->department->name,
            'department'        => ['id' => $designation->department->id, 'name' => $designation->department->name],
            'can_edit'          => $designation->can_edit,
            'can_delete'        => $designation->can_delete,
        ];
    }
}
