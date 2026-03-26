<?php

namespace Domain\User\Entities;

use Domain\User\ValueObjects\UserRole;

class User
{
    private string $id;

    private string $name;

    private string $email;

    private string $password;

    private UserRole $role;

    public function __construct(string $id, string $name, string $email, string $password, UserRole $role)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRole(): UserRole
    {
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }
}
