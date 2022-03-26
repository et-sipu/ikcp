<?php

namespace App\Transformers;

use App\Models\Comment;
use League\Fractal\TransformerAbstract;

class CommentTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Comment $comment
     *
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            'id'         => $comment->id,
            'name'       => $comment->commented->name,
            'avatar'     => $comment->commented->avatar,
            'comment'    => $comment->comment,
            'created_at' => $comment->created_at->diffForHumans(),
        ];
    }
}
