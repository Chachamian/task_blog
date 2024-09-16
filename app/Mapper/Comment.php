<?php

namespace App\Mapper;

class Comment
{
    public ?int $id;
    public ?int $user_id;
    public ?int $post_id;
    public ?string $author;
    public ?int $dateTime;
    public ?string $comment;

    public function __construct($id = null, $user_id = null, $post_id = null, $author = null, $dateTime = null, $comment = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->post_id = $post_id;
        $this->author = $author;
        $this->dateTime = $dateTime;
        $this->comment = $comment;
    }
}