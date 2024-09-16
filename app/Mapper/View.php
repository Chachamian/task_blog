<?php

namespace App\Mapper;

class View
{
    public int $id;
    public int $userId;
    public int $postId;

    public function __construct($id = null, $userId = null, $postId = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->postId = $postId;
    }
}