<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Contracts\Workflowable;
use Illuminate\Queue\SerializesModels;

class WorkflowableToPass extends Mailable
{
    use Queueable, SerializesModels;

    private $workflowable;

    /**
     * WorkflowableToPass constructor.
     *
     * @param Workflowable $workflowable
     */
    public function __construct(Workflowable $workflowable)
    {
        $this->workflowable = $workflowable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.workflowable_to_pass.subject', ['workflowable' => __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type())]))
            ->markdown('emails.workflowable_to_pass')
            ->withLink($this->workflowable->get_edit_url())
            ->withWorkflowable($this->workflowable)
            ->with('workflowable_type', $this->workflowable->get_workflowable_type());
    }
}
