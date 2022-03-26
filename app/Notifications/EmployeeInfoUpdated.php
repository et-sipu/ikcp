<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Contracts\Commentable;
use App\Models\Contracts\Workflowable;
use App\Models\Employee;
use App\Transformers\CommentTransformer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmployeeInfoUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Employee
     */
    private $employee;

    /**
     * Create a new notification instance.
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
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
//        return ['database', 'broadcast'];
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
            ->subject(__('mails.employee_updated.subject'))
            ->markdown('emails.employee_updated', ['employee_name' => $this->employee->full_name]);
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
            'subject' => __('mails.employee_updated.subject'),
            'body' => sprintf('The employee \'%s\' has updated his/her information', $this->employee->full_name),
            'link' => $this->employee->get_local_edit_url()
        ];
    }

    /**
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'subject' => __('mails.employee_updated.subject'),
            'body' => sprintf('The employee \'%s\' has updated his/her information', $this->employee->full_name),
            'link' => $this->employee->get_local_edit_url()
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
        dd($this->employee->toArray(), $exception);
    }
}
