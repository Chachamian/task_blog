<?php

namespace App\Mapper;

class Subscribe
{
    public ?int $id;
    public ?int $userId;
    public ?int $ownerId;

    public function __construct($id, $userId, $ownerId)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->ownerId = $ownerId;
    }
}