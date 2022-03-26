<?php

namespace App\Models\Traits;

use App\Models\Comment;

trait CommentableTrait
{
    /**
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @param bool $as_users
     * @return array
     */
    public function commenters($as_users = false)
    {
        $users = [];
        $this->comments->each(function ($comment) use (&$users, $as_users) {
            if($as_users) array_push($users, $comment->commented);
            else $users[$comment->commented->email] = $comment->commented; //?????????????commented
        });

        return $users;
    }

    /**
     * @return array
     */
    public function getStackHolders()
    {
        return [];
    }

    /**
     * @throws \ReflectionException
     *
     * @return string|string[]|null
     */
    public function get_commentable_type()
    {
        $reflect = new \ReflectionClass($this);

        return preg_replace('/([A-Z][a-z]*)([A-Z][a-z]*)/', '$1 $2', $reflect->getShortName());
    }

    /**
     * @throws \ReflectionException
     *
     * @return string|string[]|null
     */
    public function get_title()
    {
        return $this->get_commentable_type().' #'.$this->id;
    }
}
