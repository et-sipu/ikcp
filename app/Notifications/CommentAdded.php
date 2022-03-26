<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Contracts\Commentable;
use App\Transformers\CommentTransformer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentAdded extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * @var Commentable
     */
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
                    ->subject('New Comment')
                    ->greeting(__('mails.new_comment_assigned.hello'))
                    ->line(__('mails.new_comment_assigned.message', ['commented' => $this->comment->commented->name , 'commentable_type' => $this->comment->commentable->get_commentable_type()]))
                    ->line(__('mails.new_comment_assigned.title', ['item' => $this->comment->commentable->get_commentable_type()]).':'.  $this->comment->commentable->title)
                    ->line(__('mails.new_comment_assigned.description').": ". $this->comment->comment)
                    ->action('Follow Up', $this->comment->commentable->get_editted_url());
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
            'subject' => 'New Comment',
            'body' => sprintf('A new comment has been written by %s to something you are interested in', $this->comment->commented->name),
            'comment' => (new CommentTransformer())->transform($this->comment),
            'related_to' => $this->comment->commentable->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'id' => $this->comment->commentable->id,
            'link' => $this->comment->commentable->get_editted_url(),
            'subject' => 'New Comment',
            'body' => sprintf('A new comment has been written by %s to something you are interested in', $this->comment->commented->name),
        ]));//->onConnection('database')->onQueue('default');
    }

    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        dd($this->comment->toArray(), $exception);
    }
}
