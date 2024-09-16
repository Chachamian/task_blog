<?php

namespace App\Mapper;

class Like
{
    public ?int $id;
    public ?int $user_id;
    public ?int $post_id;

    public function __construct($id = null, $user_id = null, $post_id = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->post_id = $post_id;
    }
}