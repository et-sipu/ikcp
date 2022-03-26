<?php

namespace App\Models\Traits;

use App\Models\Comment;

trait CanComment
{
    /**
     * @param $commentable
     * @param string $commentText
     * @param int    $rate
     *
     * @return Comment
     */
    public function comment($commentable, $commentText = '')
    {
        $comment = new Comment([
            'comment'        => $commentText,
            'commented_id'   => $this->id,
            'commented_type' => static::class,
        ]);

        $commentable->comments()->save($comment); //???????????????????

        $comment->refresh();

        return $comment;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }
}
