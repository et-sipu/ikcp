<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use App\Models\Contracts\Workflowable;

/**
 * Interface TasklRepository.
 */
interface TaskRepository extends BaseRepository
{
//    /**
//     * @param $name
//     *
//     * @return Task
//     */
//    public function find($name);

    /**
     * @param array $input
     *
     * @return mixed
     */
    public function store(array $input);

    /**
     * @param Task  $task
     * @param array $input
     *
     * @return mixed
     */
    public function update(Task $task, array $input);

    /**
     * @param Task $task
     *
     * @return mixed
     */
    public function destroy(Task $task);

    /**
     * @param Workflowable $task
     * @param $new_status
     *
     * @return mixed
     */
    public function changeStatus(Workflowable $task, $new_status);
}
