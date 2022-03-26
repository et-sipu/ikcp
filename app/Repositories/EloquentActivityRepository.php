<?php

namespace App\Repositories;

use Exception;
use App\Models\Activity;
use App\Models\Transition;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\ActivityRepository;

/**
 * Class EloquentActivityRepository.
 */
class EloquentActivityRepository extends EloquentBaseRepository implements ActivityRepository
{
    /**
     * EloquentActivityRepository constructor.
     *
     * @param Activity $activity
     */
    public function __construct(Activity $activity)
    {
        parent::__construct($activity);
    }

//    /**
//     * @param $name
//     *
//     * @return Activity
//     */
//    public function find($name)
//    {
//        /** @var Activity $activity */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Activity
     */
    public function store(array $input)
    {
        /** @var Activity $activity */
        $activity = $this->make(array_except($input, []));
        if (! auth()->user()->head_of) {
            throw new GeneralException(__('exceptions.backend.activities.create'));
        }

        $activity->action_by = $input['action_by']['id'];
        $activity->action_from = auth()->user()->head_of->id;
//        $activity->action_from = 1;//auth()->user()->head_of->id;

        $activity->description = nl2br($activity->description);

//        if ($this->find($activity->name)) {
//            throw new GeneralException(__('exceptions.backend.activities.already_exist'));
//        }

        DB::transaction(function () use ($activity) {
            if (! $activity->save()) {
                throw new GeneralException(__('exceptions.backend.activities.create'));
            }

            $activity->transitions()->save(new Transition(['status' => 'pending']));

            return true;
        });

        return $activity;
    }

    /**
     * @param Activity $activity
     * @param array    $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Activity
     */
    public function update(Activity $activity, array $input)
    {
//        if (($existingActivity = $this->find($activity->name))
//          && $existingActivity->id !== $activity->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.activities.already_exist'));
//        }

        DB::transaction(function () use ($activity, $input) {
            if (! $activity->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.activities.update'));
            }

            return true;
        });

        return $activity;
    }

    /**
     * @param Activity $activity
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Activity $activity)
    {
        if (! $activity->delete()) {
            throw new GeneralException(__('exceptions.backend.activities.delete'));
        }

        return true;
    }

    /**
     * @param Activity $activity
     * @param $approved_cost
     *
     * @throws \Throwable
     *
     * @return mixed|void
     */
    public function approve(Activity $activity, $approved_cost)
    {
        if (isset($approved_cost)) {
            $activity->approved_cost = $approved_cost;
        }

        return $this->changeStatus($activity, 'approve');
    }

    /**
     * @param Activity $activity
     * @param $transition
     *
     * @throws \Throwable
     *
     * @return bool
     */
    public function changeStatus(Activity $activity, $transition)
    {
        DB::transaction(function () use ($activity, $transition) {
            $activity->workflow_apply($transition);

            $activity->save();

            $activity->transitions()->save(new Transition(['status' => $activity->status]));

            return true;
        });

        return true;
    }
}
