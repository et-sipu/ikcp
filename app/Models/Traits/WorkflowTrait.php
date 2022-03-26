<?php

namespace App\Models\Traits;

use Workflow;
use App\Models\User;
use App\Models\Transition;
use Illuminate\Support\Str;
use App\Models\Contracts\Workflowable;
use App\Events\WorkflowableTransmitted;
use Illuminate\Database\Eloquent\Collection;

trait WorkflowTrait
{
    protected static function bootWorkflowTrait()
    {
        static::created(function (Workflowable $model) {
//            $model->transitions()->save(new Transition(['status' => (isset($model::$initial_state)) ? $model::$initial_state : 'hod_approving', 'user_id' => auth()->user()->id]));
            $model->transitions()->save(new Transition(['status' => $model->status ?: $model::$initial_state, 'user_id' => auth()->user()->id]));

            event(new WorkflowableTransmitted($model->refresh()));
        });
    }

    public function workflow_apply($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->apply($this, $transition);
    }

    public function workflow_can($transition, $workflow = null)
    {
        return Workflow::get($this, $workflow)->can($this, $transition);
    }

    public function workflow_transitions()
    {
        return Workflow::get($this)->getEnabledTransitions($this);
    }

    public function transition($status)
    {
        return $this->transitions->where('status', $status)->first();
    }

    public function transitions()
    {
        return $this->morphMany(Transition::class, 'transitionable')->orderBy('id', 'desc');
    }

    /**
     * @return User
     */
    public function get_owner()
    {
        return $this->requester;
    }

    /**
     * @throws \ReflectionException
     *
     * @return User[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Collection
     */
    public function get_receivers()
    {
        if ('hod_approving' === $this->status && $this->requester && $this->requester->employee->department) {
            return (new Collection())->add($this->requester->employee->department->hod);
        }

        $permission_name = 'pass '.$this->status.' '.$this->get_workflowable_display_name();

        return User::whereHas('roles', function ($query) use ($permission_name) {
            $query->whereHas('permissions', function ($q) use ($permission_name) {
                $q->where('name', $permission_name);
            });
        })->get();
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    public function get_workflowable_type()
    {
        $reflect = new \ReflectionClass($this);

        return $reflect->getShortName();
    }

    /**
     * @throws \ReflectionException
     *
     * @return mixed
     */
    public function get_workflowable_display_name()
    {
        return str_replace('_', ' ', $this->get_workflowable_name());
    }

    /**
     * @throws \ReflectionException
     *
     * @return string
     */
    public function get_workflowable_name()
    {
        return Str::plural(Str::snake($this->get_workflowable_type()));
    }

    public function reached_minimum_print_state()
    {
        if ((isset($this::$minimal_print_state) && $this->transition($this::$minimal_print_state) || 'dec_approving' === $this->status)) {
            return true;
        }

        return false;
    }
}
