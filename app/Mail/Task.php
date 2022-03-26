<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Queue\SerializesModels;

class Task extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Task
     */
    private $task;

    /**
     * @param \App\Models\SeafarerContract $seafarerContract
     */
    public function __construct(\App\Models\Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.new_task_assigned.subject'))
            ->markdown('emails.new_task')
            ->withLink($this->task->get_url())
            ->withTask($this->task);
    }
}
