<?php

namespace App\Mapper;

class User
{
    public ?int $id;
    public ?string $userName;
    public ?string $email;
    public ?string $password;
    public ?int $role;

    public function __construct($id = null, $userName = null, $email = null, $password = null, $role = null)
    {
        $this->id = $id;
        $this->userName = $userName;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
}