<?php

namespace App\Transformers;

use App\Models\Holiday;
use League\Fractal\TransformerAbstract;

class HolidayTransformer extends TransformerAbstract
{
    private $as_events;

    /**
     * HolidayTransformer constructor.
     *
     * @param $as_events
     */
    public function __construct($as_events = false)
    {
        $this->as_events = $as_events;
    }

    public function transform(Holiday $holiday)
    {
        if ($this->as_events) {
            return [
                'id'          => $holiday->id,
                'title'       => $holiday->occasion,
                'start'       => $holiday->start_date,
                'end'         => $holiday->end_date,
                'content'     => '<i class="fas fa-umbrella-beach">',
                'description' => $holiday->description,
                'class'       => 'leisure',
            ];
        }

        return [
            'id'          => $holiday->id,
            'occasion'    => $holiday->occasion,
            'start_date'  => $holiday->start_date,
            'end_date'    => $holiday->end_date,
            'description' => $holiday->description,
            'can_edit'    => $holiday->can_edit,
            'can_delete'  => $holiday->can_delete,
        ];
    }
}
