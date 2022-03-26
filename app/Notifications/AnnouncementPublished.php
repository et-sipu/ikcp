<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementPublished extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Announcement
     */
    private $announcement;
    /**
     * @var array
     */
    private $cc;

    /**
     * Create a new notification instance.
     *
     * @param Announcement $announcement
     * @param array $cc
     */
    public function __construct(Announcement $announcement, $cc = [])
    {
        $this->announcement = $announcement;
        $this->cc = $cc;
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
        $message = (new MailMessage)
            ->subject($this->announcement->subject)
            ->markdown('emails.announcement', ['subject' => $this->announcement->subject, 'content' => $this->announcement->content])
            ->cc($this->cc);

        foreach ($this->announcement->attachments_info as $attachment) {
            $message->attach($attachment['path']);
        }

        return $message;
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
            'subject' => 'A new announcement have been published',
            'body' => sprintf('A new announcement with subject \'%s\' have been published recently', $this->announcement->subject),
            'link' => $this->announcement->get_local_edit_url()
        ];
    }

    /**
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'subject' => 'A new announcement have been published',
            'body' => sprintf('A new announcement with subject \'%s\' have been published recently', $this->announcement->subject),
            'link' => $this->announcement->get_local_edit_url()
        ]));
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
