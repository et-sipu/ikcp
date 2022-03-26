<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExceptionOccurred extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var \Exception
     */
    private $exception;
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param \Exception $exception
     * @param User $user
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', 'mail'];
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
                    ->subject(__('mails.alert.subject', ['app_name' => config('app.name')]))
                    ->markdown('emails.exception-occurred', ['message' => $this->exception['message'], 'user' => isset($this->exception['user']) ? $this->exception['user']->name : '', 'url' => $this->exception['url'], 'trace' => nl2br($this->exception['trace'])]);

//                    ->greeting(__('mails.workflowable_to_pass.hello'))
//                    ->line(__('mails.workflowable_to_pass.message', ['workflowable' => __('mails.workflowable_to_pass.types.'. $this->workflowable->get_workflowable_type())]))
//                    ->line(__('mails.workflowable_to_pass.requester') .':'. $this->workflowable->requester->name  )
//                    ->action('Follow Up', $this->workflowable->get_local_edit_url());
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
            'subject' => __('mails.alert.subject', ['app_name' => config('app.name')]),
            'body' => __('mails.alert.full_message', ['message' => $this->exception['message'], 'user' => isset($this->exception['user']) ? $this->exception['user']->name : '', 'url' => $this->exception['url']]),
            'trace' => nl2br($this->exception['trace'])
        ];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'subject' => __('mails.alert.subject', ['app_name' => config('app.name')]),
            'body' => __('mails.alert.full_message', ['message' => $this->exception['message'], 'user' => isset($this->exception['user']) ? $this->exception['user']->name : '', 'url' => $this->exception['url']]),
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
//        dd($this->exception, $exception);
    }
}
