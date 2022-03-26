<?php

namespace App\Repositories;

use Exception;
use App\Models\Task;
use App\Events\TaskAssigned;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\Contracts\TaskRepository;
use App\Repositories\Traits\TransmittableTrait;

/**
 * Class EloquentTaskRepository.
 */
class EloquentTaskRepository extends EloquentBaseRepository implements TaskRepository
{
    use TransmittableTrait;

    /**
     * EloquentTaskRepository constructor.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        parent::__construct($task);
    }

//    /**
//     * @param $name
//     *
//     * @return Task
//     */
//    public function find($name)
//    {
//        /** @var Task $task */
//        return false; //$this->query()->whereName($name)->first();
//    }

    /**
     * @param array $input
     *
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Task
     */
    public function store(array $input)
    {
        /** @var Task $task */
        $input['assigned_department_id'] = $input['assigned_department']['id'];
        $task = $this->make(array_except($input, []));
        $task->assigner_id = auth()->user()->id;

//        if ($this->find($task->name)) {
//            throw new GeneralException(__('exceptions.backend.tasks.already_exist'));
//        }

        DB::transaction(function () use ($task) {
            if (! $task->save()) {
                throw new GeneralException(__('exceptions.backend.tasks.create'));
            }

            event(new TaskAssigned($task));

            return true;
        });

        return $task;
    }

    /**
     * @param Task  $task
     * @param array $input
     *
     * @throws Exception
     * @throws \Exception|\Throwable
     *
     * @return \App\Models\Task
     */
    public function update(Task $task, array $input)
    {
//        if (($existingTask = $this->find($task->name))
//          && $existingTask->id !== $task->id
//        ) {
//            throw new GeneralException(__('exceptions.backend.tasks.already_exist'));
//        }
        $task->assigned_department_id = $input['assigned_department']['id'];

        DB::transaction(function () use ($task, $input) {
            if (! $task->update(array_except($input, []))) {
                throw new GeneralException(__('exceptions.backend.tasks.update'));
            }

            return true;
        });

        return $task;
    }

    /**
     * @param Task $task
     *
     * @throws \Exception|\Throwable
     *
     * @return bool|null
     */
    public function destroy(Task $task)
    {
        if (! $task->delete()) {
            throw new GeneralException(__('exceptions.backend.tasks.delete'));
        }

        return true;
    }
}
