<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Queue\SerializesModels;

class TaskAssigned
{
    use SerializesModels;

    /**
     * @var Task
     */
    public $task;

    /**
     * TaskAssigned constructor.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
