<?php

namespace App\Transformers;

use App\Models\Activity;
use League\Fractal\TransformerAbstract;

class ActivityTransformer extends TransformerAbstract
{
    public function transform(Activity $activity)
    {
        return [
            'id'                     => $activity->id,
            'title'                  => $activity->title,
            'description'            => $activity->description,
            'start_date'             => $activity->start_date,
            'completion_date'        => $activity->transition('done') ? $activity->transition('done')->in_date : null,
            'cost'                   => $activity->cost,
            'approved_cost'          => $activity->approved_cost ?: $activity->cost,
            'action_by_department'   => ['id' => $activity->action_by, 'name' => $activity->by_department->name],
            'action_from_department' => ['id' => $activity->action_from, 'name' => $activity->from_department->name],
            'status'                 => $activity->status,
            'status_at'              => $activity->transition($activity->status) ? $activity->transition($activity->status)->in_date : null,
            'can_edit'               => (bool) $activity->can_edit,
            'can_delete'             => (bool) $activity->can_delete,
            'can_mark_as_done'       => (bool) $activity->can_mark_as_done,
            '_showDetails'           => false,
            '_rowVariant'            => '',
        ];
    }
}
