<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Comment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    private $comment;

    public function __construct(\App\Models\Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mails.new_comment_assigned.subject'))
            ->markdown('emails.new_comment')
            ->withLink($this->comment->commentable->get_editted_url())
            ->with('commentable_type', $this->comment->commentable->get_commentable_type())
            ->with('commentable_title', $this->comment->commentable->get_title())
            ->withComment($this->comment);
    }
}
