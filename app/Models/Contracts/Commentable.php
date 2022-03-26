<?php

namespace App\Models\Contracts;

interface Commentable
{
    public function comments();

    public function commenters($as_users = false);

    public function getStackHolders();

    public function get_commentable_type();

    public function get_editted_url();

    public function get_title();
}
