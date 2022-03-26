<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Contracts\Commentable;
use App\Models\Contracts\Workflowable;
use App\Transformers\CommentTransformer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ApproveRequired extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Workflowable
     */
    private $workflowable;

    /**
     * Create a new notification instance.
     *
     * @param Workflowable $workflowable
     */
    public function __construct(Workflowable $workflowable)
    {
        $this->workflowable = $workflowable;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['mail', 'database'];
        return ['database', 'broadcast', 'mail'];
//        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('mails.workflowable_to_pass.subject', ['workflowable' => __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type())]))
                    ->greeting(__('mails.workflowable_to_pass.hello'))
                    ->line(__('mails.workflowable_to_pass.message', ['workflowable' => __('mails.workflowable_to_pass.types.'. $this->workflowable->get_workflowable_type())]))
                    ->line(__('mails.workflowable_to_pass.requester') .':'. $this->workflowable->requester->name  )
                    ->action('Follow Up', $this->workflowable->get_local_edit_url());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'subject' => __('mails.workflowable_to_pass.subject', ['workflowable' => __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type())]),
            'body' => sprintf('You got a new %s by %s to pass', __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type()), $this->workflowable->requester->name),
            'link' => $this->workflowable->get_local_edit_url()
        ];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'id' => $this->workflowable->id,
            'link' => $this->workflowable->get_local_edit_url(),
            'subject' => __('mails.workflowable_to_pass.subject', ['workflowable' => __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type())]),
            'body' => sprintf('You got a new %s by %s to pass', __('mails.workflowable_to_pass.types.'.$this->workflowable->get_workflowable_type()), $this->workflowable->requester->name),
        ]));//->onConnection('database')->onQueue('default');
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        dd($this->workflowable->toArray(), $exception);
    }
}
