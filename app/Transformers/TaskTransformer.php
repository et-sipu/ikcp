<?php

namespace App\Transformers;

use App\Models\Task;
use League\Fractal\TransformerAbstract;

class TaskTransformer extends TransformerAbstract
{
    public function transform(Task $task)
    {
        return [
            'id'                  => $task->id,
            'title'               => $task->title,
            'description'         => $task->description,
            'completion_date'     => $task->transition('done') ? $task->transition('done')->in_date : null,
            'assigned_department' => ['id' => $task->assigned_department_id, 'name' => $task->assigned_department->name],
            'created_at'          => $task->created_at->format('Y-m-d'),
            'status'              => $task->status,
            'can_edit'            => $task->can_edit,
            'can_delete'          => $task->can_delete,
            'can_mark_as_done'    => (bool) $task->can_mark_as_done,
            '_showDetails'        => false,
            '_rowVariant'         => '',
        ];
    }
}
