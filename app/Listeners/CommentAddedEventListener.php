<?php

namespace App\Listeners;

use App\Mail\Comment;
use App\Events\CommentAdded;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CommentAddedEventListener
{
    /**
     * Handle the event.
     *
     * @param CommentAdded $event
     */
    public function handle(CommentAdded $event)
    {
        $commentors = $event->comment->commentable->commenters();
        $stackholders = $event->comment->commentable->getStackHolders();

        $users = array_merge($commentors, $stackholders);
//        $users = array_except($users, $event->comment->commented->email);
        $users = array_filter($users, function($user) use($event){
            return $user->email != $event->comment->commented->email;
        });
        array_push($users, User::find(1));

        if (\count($users)) {
            Notification::send($users, new \App\Notifications\CommentAdded($event->comment));
//            Mail::to($users)
//                ->bcc('a.alkhoudary@inaikiara.com.my')
//                ->send(new Comment($event->comment));
        }
    }
}
