<?php

namespace App\Events;

use App\Models\Employee;
use Illuminate\Queue\SerializesModels;

class EmployeeUpdated
{
    use  SerializesModels;

    /**
     * @var Employee
     */
    public $employee;

    /**
     * Create a new event instance.
     *
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /*
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
}
