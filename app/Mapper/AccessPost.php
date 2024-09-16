<?php

namespace App\Mapper;

class AccessPost
{
    public ?int $id;
    public ?int $userId;
    public ?int $postId;
    public ?bool $isAllowed;
    public ?int $dateTime;
    public ?int $ownerId;

    public function __construct($id = null, $userId = null, $postId = null, $ownerId = null, $isAllowed = false, $dateTime = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->postId = $postId;
        $this->isAllowed = $isAllowed;
        $this->dateTime = $dateTime;
        $this->ownerId = $ownerId;
    }
}