<?php

namespace App\Repositories\Traits;

use App\Models\Transition;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Models\Contracts\Workflowable;
use App\Events\WorkflowableTransmitted;
use Illuminate\Support\Facades\Validator;

trait TransmittableTrait
{
    public function get_validation_rules($transition)
    {
        return [];
    }

    public function get_validation_messages($transition)
    {
        return [];
    }

    public function check_possibility(Workflowable $workflowable, $transition)
    {
        return true;
    }

    public function make_transformation(Workflowable $workflowable, &$transition)
    {
        return true;
    }

    /**
     * @param Workflowable $workflowable
     * @param $transition
     *
     * @throws \Throwable
     *
     * @return bool
     */
    public function changeStatus(Workflowable $workflowable, $transition)
    {
        $rules = $this->get_validation_rules($transition);

        $messages = $this->get_validation_messages($transition);

        $validator = Validator::make($workflowable->toArray(), $rules, $messages);

        if ($validator->fails()) {
            throw new GeneralException($validator->errors()->first());
        }

        $this->check_possibility($workflowable, $transition);

        $this->make_transformation($workflowable, $transition);

        DB::transaction(function () use ($workflowable, $transition) {
            $workflowable->workflow_apply($transition);

            $workflowable->save();

            $workflowable->transitions()->save(new Transition(['status' => $workflowable->status, 'user_id' => auth()->user()->id, 'transition' => $transition]));

            event(new WorkflowableTransmitted($workflowable));

            return true;
        });

        return true;
    }
}
