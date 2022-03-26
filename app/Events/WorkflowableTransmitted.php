<?php

namespace App\Events;

use App\Models\Contracts\Workflowable;
use Illuminate\Queue\SerializesModels;

class WorkflowableTransmitted
{
    use SerializesModels;

    /**
     * @var Workflowable
     */
    public $workflowable;

    /**
     * WorkflowableTransmitted constructor.
     *
     * @param Workflowable $workflowable
     */
    public function __construct(Workflowable $workflowable)
    {
        $this->workflowable = $workflowable;
    }
}
